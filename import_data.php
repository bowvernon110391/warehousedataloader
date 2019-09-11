<?php
include 'common.php';

function globFolder($srcfolder, $queryfilename, $schemeName, $db) {
	foreach (glob($srcfolder . '/*') as $fname) {
		// if it's a folder, glob recursively
		if (is_dir($fname)) {
			globFolder($fname, $queryfilename, $schemeName, $db);
		} else if ( strtoupper(substr($fname, -4)) == '.CTL' ) {
			$realFilename = realpath($fname);
			// check for dups
			if (fileIsValid($realFilename, $schemeName, $db)) {
				// grab filename with fullpath
				// echo "--------------------------------------------------------------\r\n";
				echo "PROCESSING: $realFilename\r\n";
				$rowInserted = inputSourceCTL($realFilename, $queryfilename, $db);

				if ($rowInserted) {
					// mark success
					$res = markFileHistory($realFilename, $schemeName, $db);
				}
				echo "--------------------------------------------------------------\r\n";
			} else {
				echo "SKIPPED: $realFilename for not being valid.\r\n";
			}
			
		}
	}
}

/**
* start of script here
*/
echo "********************************************\r\n";
echo "IMPORT STARTED @ " . date('d/m/Y H:i:s') . "\r\n";
echo "********************************************\r\n";
// read settings
readSettings("settings.json");

// open database
$db = openDB($host, $username, $password, $dbname);

if (!$db) {
	die("DB FAILED TO OPEN!");
}

// read import schema
$importSchema = array(
	);

$importSchemaFilename = 'import_schema.json';

if (file_exists($importSchemaFilename)) {
	$importSchema = json_decode(file_get_contents($importSchemaFilename));
}

foreach ($importSchema as $schema) {
	// print_r($schema);
	echo "\r\n\r\nEXECUTING SCHEMA: " . $schema->name . "\r\n";
	echo "sql: {$schema->sql}, src: {$schema->src}\r\n";
	echo "--------------------------------------------------------------\r\n";

	$queryfilename = realpath($schema->sql);
	$srcfolder = stripslashes($schema->src);
	// start globbing
	globFolder($srcfolder, $queryfilename, $schema->name, $db);
}

echo "\r\n\r\n********************************************\r\n";
echo "IMPORT ENDED @ " . date('d/m/Y H:i:s') . "\r\n";
echo "********************************************\r\n";

echo "\r\n\r\n";

?>