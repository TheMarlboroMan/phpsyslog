<?php
//!PHP4 version: provides the same interface that the PHP5 version even tough
//!the inner workings are completely different... There will be holes all over
//!the place if you don't stick to the interface!.

DEFINE('DEFAULT_PHP_SYSLOG_FACILITY_NAME', 'phpsyslog');

function phpsyslog_init($_facility, $_flags=LOG_PID | LOG_ODELAY, $_type=LOG_LOCAL0) {
	return phpsyslog::init($_facility, $_flags, $_type);
}

function phpsyslog_shutdown() {
	phpsyslog::shutdown();
}

function qlog($_level, $_msg) {
	
	$log=phpsyslog::get();
	if(!$log) {
		phpsyslog_init(get_default_phpsyslog_facility_name());
		phpsyslog::get()->log($_level, $_msg);
	}
	else {
		$log->log($_level, $_msg);
	}
}

function get_default_phpsyslog_facility_name() {
	return DEFAULT_PHP_SYSLOG_FACILITY_NAME;
}

$_phpsysloginstance=null;

//!Nothing prevents us from instantiating the class, as there are no visibility
//!modifiers... Oh well, pray the user sticks to the interface exposed in the
//!PHP5 version!.

class phpsyslog {

	var $facility=null;

	//! Do man syslog if you want to know more about $_flags and $_type.
	function init($_facility, $_flags=LOG_PID | LOG_ODELAY, $_type=LOG_LOCAL0) {

		global $_phpsysloginstance;
		if($_phpsysloginstance) {
			return false;	//No exception handling makes me sad.
		}

		$_phpsysloginstance=new phpsyslog($_facility, $_flags, $_type);
		$_phpsysloginstance->log(LOG_INFO, $_facility." log (version 4) was init");
		return $_phpsysloginstance;
	}

	function &get() {
		global $_phpsysloginstance;
		return $_phpsysloginstance; //Null or not. I am sorry.
	}

	function shutdown() {
		global $_phpsysloginstance;
		if(!$_phpsysloginstance) {
			return false;	//Yet again, no exception handling.
		}
		$_phpsysloginstance->log(LOG_INFO, $_phpsysloginstance->facility." will shutdown");
		$_phpsysloginstance=null;
		closelog();
	}

	function stlog($_level, $_msg) {
		global $_phpsysloginstance;
		if(!$_phpsysloginstance) {
			return false;	//This is getting tiresome...
		}
		$_phpsysloginstance->log($_level, $_msg);
	}
	
	function log($_level, $_msg) {
		syslog($_level, translate_phpsyslog_level($_level).' : '.$_msg);
	}

	function phpsyslog($_facility, $_flags=LOG_PID | LOG_ODELAY, $_type=LOG_LOCAL0) {
		$this->facility=$_facility;
		if(!openlog($this->facility, $_flags, $_type)) {
			return false;	//Meeeh.
		}
	}

	//Now, this would be a rare case.
	function __constructor($_facility, $_flags=LOG_PID | LOG_ODELAY, $_type=LOG_LOCAL0) {
		$this->phpsyslog($_facility, $_flags, $_type);
	}
}
