<?php
//!PHP4 version: provides the same interface that the PHP5 version even tough
//!the inner workings are completely different... There will be holes all over
//!the place if you don't stick to the interface!.

DEFINE('DEFAULT_PHP_SYSLOG_NAME', 'phpsyslog');
define('LOGPID_OR_LOGODELAY', 5);

function phpsyslog_init($_name, $_flags=LOGPID_OR_LOGODELAY, $_facility=LOG_LOCAL0) {
	return phpsyslog::init($_name, $_flags, $_facility);
}

function phpsyslog_shutdown() {
	phpsyslog::shutdown();
}

function qlog($_level, $_msg) {
	
	$log=phpsyslog::get();
	if(!$log) {
		phpsyslog_init(get_default_phpsyslog_name());
		phpsyslog::get()->log($_level, $_msg);
	}
	else {
		$log->log($_level, $_msg);
	}
}

function get_default_phpsyslog_name() {
	return DEFAULT_PHP_SYSLOG_NAME;
}

$_phpsysloginstance=null;

//!Nothing prevents us from instantiating the class, as there are no visibility
//!modifiers... Oh well, pray the user sticks to the interface exposed in the
//!PHP5 version!.

class phpsyslog {

	var $name=null;

	//! Do man syslog if you want to know more about $_flags and $_facility.
	function init($_name, $_flags=LOGPID_OR_LOGODELAY, $_facility=LOG_LOCAL0) {

		global $_phpsysloginstance;
		if($_phpsysloginstance) {
			return false;	//No exception handling makes me sad.
		}

		$_phpsysloginstance=new phpsyslog($_name, $_flags, $_facility);
		$_phpsysloginstance->log(LOG_INFO, $_name." log (version 4) was init");
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
		$_phpsysloginstance->log(LOG_INFO, $_phpsysloginstance->name." will shutdown");
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

	function phpsyslog($_name, $_flags=LOGPID_OR_LOGODELAY, $_facility=LOG_LOCAL0) {
		$this->name=$_name;
		if(!openlog($this->name, $_flags, $_facility)) {
			return false;	//Meeeh.
		}
	}

	//Now, this would be a rare case.
	function __constructor($_name, $_flags=LOGPID_OR_LOGODELAY, $_facility=LOG_LOCAL0) {
		$this->phpsyslog($_name, $_flags, $_facility);
	}
}
