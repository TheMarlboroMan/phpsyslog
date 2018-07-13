<?php
//Not an autoload, but hey...
list($version, $major, $minor)=explode('.', phpversion());
$dir=__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'tools'.DIRECTORY_SEPARATOR.'phpsyslog'.DIRECTORY_SEPARATOR;
if($version < 5) {
	require_once($dir."phpsyslog4.php");
}
else {
	require_once($dir."phpsyslog5.php");
}
