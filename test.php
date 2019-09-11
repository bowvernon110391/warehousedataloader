<?php
$name  = 'shit.ctl';

$s = json_decode(file_get_contents('settings.json'));
print_r($s);

// gonna add csv tester below, to test csv import
// as of now, csv import is rather fragile, not all case is covered
// because we depends on php's csv reader
?>