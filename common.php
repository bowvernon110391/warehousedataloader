<?php
// global const
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'data_dt';

$maxFileAgeInDays = 40;

// check if file is valid
function fileIsValid($fname, $schema, $db) {
	global $maxFileAgeInDays;

	// valid if exists
	if (!file_exists($fname)) {
		echo "INVALID_FILE_NOT_EXIST: $fname\r\n";
		return false;
	}

	// valid if not too old
	$age = dayOld($fname);
	if ($age >= $maxFileAgeInDays) {
		echo "INVALID_FILE_TOO_OLD: $fname, $age day-old\r\n";
		return false;
	}

	// all else being equal, check for db
	if (isFileAlreadyImported($fname, $schema, $db)) {
		echo "INVALID_FILE_ALREADY_IMPORTED: $fname\r\n";
		return false;
	}

	return true;
}

// for reading settings
function readSettings($fname) {
	global $host, $username, $password, $dbname, $maxFileAgeInDays;

	if (!file_exists($fname))
		return false;

	$settings = json_decode(file_get_contents($fname));

	if (!$settings)
		return false;

	// start setting things
	if (isset($settings->database->host)) {
		$host = $settings->database->host;
		echo "SETTINGS: set host to -> $host\r\n"; 
	}

	if (isset($settings->database->username)) {
		$username = $settings->database->username;
		echo "SETTINGS: set username to -> $username\r\n";
	}

	if (isset($settings->database->password)) {
		$password = $settings->database->password;
		echo "SETTINGS: set password to -> $password\r\n";
	}

	if (isset($settings->database->dbname)) {
		$dbname = $settings->database->dbname;
		echo "SETTINGS: set dbname to -> $dbname\r\n";
	}

	if (isset($settings->maxFileAgeInDays)) {
		$maxFileAgeInDays = $settings->maxFileAgeInDays;
		echo "SETTINGS: set maxFileAgeInDays to -> $maxFileAgeInDays\r\n";
	}
}

// for calculating days old
function dayOld($filename) {
	if (!file_exists($filename))
		return -1;

	$age = date_diff( date_create(), date_create("@" . filemtime($filename)) );
	return $age->d;
}


// for opening database
function openDB($host, $username, $password, $dbname) {
	$dsn = "mysql:dbname=$dbname;host=$host";

	try {
		$dbhandle = new PDO($dsn, $username, $password, array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_EMULATE_PREPARES => false
			));	
		return $dbhandle;
	} catch (Exception $e) {
		echo 'DBERROR: ' . $e->getMessage() . "\r\n";
	}
	return null;
}

// check if file already exists
function isFileAlreadyImported($filename, $schema, $dbh) {
	$query = "SELECT * FROM import_history WHERE filename = ? AND scheme = ?";

	if ($dbh) {
		try {
			$stmt = $dbh->prepare($query);
			$stmt->execute(array(
				$filename,
				$schema
				));	

			return $stmt->rowCount() >= 1;
		} catch (PDOException $e) {
			echo "Error DB: " . $e->getMessage() . "\r\n";
			return false;
		}
	}

	return false;
}

// INSERT Into import history
function markFileHistory($filename, $schema, $dbh) {
	$query = "INSERT INTO import_history(`filename`, `scheme`) VALUES (?, ?);";

	if ($dbh) {
		try {
			$stmt = $dbh->prepare($query);
			return $stmt->execute(array(
				$filename,
				$schema
				));	
		} catch (PDOException $e) {
			echo "Error DB: " . $e->getMessage() . "\r\n";
			return false;
		}
	}

	return false;
}

// CHECK IF FILE ALREADY IMPORTED BASED ON FILENAME



// INSERT INTO database depending on the source file and query string file
function inputSourceCTL($ctlfilename, $queryfilename, $dbh) {

	if (!file_exists($ctlfilename)) {
		echo $ctlfilename . ' does not exist!\r\n';
		return false;
	}

	if (!file_exists($queryfilename)) {
		echo 'query ' . $queryfilename . ' does not exist!\r\n';
		return false;
	}

	$stmt = null;

	if (!$dbh) {
		echo "Database not opened!\r\n";
		return false;
	} else {
		// build statement
		try {
			$stmt = $dbh->prepare(file_get_contents($queryfilename));
			echo "Insert statement prepared.\r\n";	
		} catch (PDOException $e) {
			echo "Error preparing statement: " . $e->getMessage() . "\r\n";	
			return false;
		}
	}

	echo "opening file: " . $ctlfilename . "\r\n";

	$fp = fopen($ctlfilename, "r");

	// initial status
	$status = array(
		'STARTED' => false,
		'ROWCOUNT' => 0,
		'ERRORCOUNT' => 0,
		'LASTERROR' => '',
		'ATTEMPTED' => 0
		);


	// must skip until begin data
	while ($row = fgetcsv($fp, 2048, ";", '"')) {
		// update start flag
		if (count($row) >= 1 && !$status['STARTED']) {
			// found the string BEGINDATA
			if ($row[0] == 'BEGINDATA') {
				$status['STARTED'] = true;
				$status['ROWCOUNT'] = 0;
				$status['ATTEMPTED'] = 0;
				continue;
			}
		}

		$maxLen = 80;
		// now, do something if we're started
		if ($status['STARTED'] && count($row) >= 12) {
			// insert into database
			try {
				$res = $stmt->execute($row);

				// $poop = implode(' | ', $row);
				echo "\r>inserted row(s)... {$status['ROWCOUNT']}";
				// $poopTrimmed = substr($poop, 0, $maxLen) . '...';

				// echo "[" .$status['ROWCOUNT'] . "]-> " . $poopTrimmed . "\r\n";
				// increase rowcount (actual inserted row)
				$status['ROWCOUNT'] += $stmt->rowCount();
				$status['ATTEMPTED'] ++;
			} catch (PDOException $e) {
				echo "Error insert: " . $e->getMessage() . "\r\n";

				$status['ERRORCOUNT']++;
				$status['LASTERROR'] = $e->getMessage();
			}

			// sleep every 100000 rows
			$sleepCounter = 100000;
			$sleepDurationMS = 1300;

			if ( $status['ROWCOUNT'] % $sleepCounter == 0 ) {
				sleep($sleepDurationMS/1000.0);
			}
			
		} else {
			// echo "IGNORING THIS LINE...\r\n";
		}
	}

	// close the file handle
	fclose($fp);

	// log status
	echo "\rINSERTED: {$status['ROWCOUNT']} rows of {$status['ATTEMPTED']}, ERROR: {$status['ERRORCOUNT']} \r\n";

	return $status['ROWCOUNT'];
}

?>