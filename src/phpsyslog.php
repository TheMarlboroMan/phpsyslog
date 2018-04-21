<?php

function translate_phpsyslog_level($_lvl) {
	switch($_lvl) {
		//I love useless breaks.
		case LOG_EMERG: return "[EMERGENCY]"; break;	//system is unusable
		case LOG_ALERT: return "[ALERT]"; break;	//action must be taken immediately
		case LOG_CRIT: return "[CRITICAL]"; break;	//critical conditions
		case LOG_ERR: return "[ERROR]"; break;		//error conditions
		case LOG_WARNING: return "[WARNING]"; break;	//warning conditions
		case LOG_NOTICE: return "[NOTICE]"; break;	//normal, but significant, condition
		case LOG_INFO: return "[INFO]"; break;		//informational message
		case LOG_DEBUG: return "[DEBUG]"; break;	//debug-level message
		default: return "[???]"; break;			//WTF did you do???.
	}
}

list($version, $major, $minor)=explode('.', phpversion());
if($version < 5) {
	require("phpsyslog4.php");
}
else {
	require("phpsyslog5.php");
}
