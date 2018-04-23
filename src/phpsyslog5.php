<?php

//Stupid PHP won't let us do LOG_PID | LOG_ODELAY when defining a default parameter.
define('LOGPID_OR_LOGODELAY', 5);

//!Free function to init the syslog, which is equivalent to syslog::init();
function phpsyslog_init($_name, $_flags=LOGPID_OR_LOGODELAY, $_facility=LOG_LOCAL0) {

	try {
		return phpsyslog::init($_name, $_flags, $_facility);
	}
	catch(\Exception $e) {
		return phpsyslog::get();
	}
}

//!Free function to shut the syslog, which is equivalent to syslog::shutdown();
function phpsyslog_shutdown() {
	try {
		phpsyslog::shutdown();
	}
	catch(\Exception $e) {
		//Ok.
	}
}

//!Enables to quickly send a log. If the logger was not init, it is started
//!with a default name, which will prevent it appearing on the correct log
//!files. Just init the log before or use phpsyslog::get()->log(), 
//!phpsyslog::stlog()

function qlog($_level, $_msg) {

	try {
		phpsyslog::get()->log($_level, $_msg);
	}
	catch(\Exception $e) {
		phpsyslog::init(get_default_phpsyslog_name())->log($_level, $_msg);
	}
}

function get_default_phpsyslog_name() {
	return phpsyslog::_DEFAULT_APP_NAME;
}

//Implemented as a singleton with a few security measures.
class phpsyslog {

	const _DEFAULT_APP_NAME="phpsyslog";

	private static $instance=null;
	private $name=null;

	//! Do man syslog if you want to know more about $_flags and $_facility.
	public static function &init($_name, $_flags=LOGPID_OR_LOGODELAY, $_facility=LOG_LOCAL0) {
		if(self::$instance) {
			throw new \Exception("Cannot init the log twice");
		}
		self::$instance=new phpsyslog($_name, $_flags, $_facility);
		self::$instance->log(LOG_INFO, $_name." log (version 5) was init");
		return self::$instance;
	}

	public static function &get() {
		if(!self::$instance) {
			throw new \Exception("The log was not init");
		}
		return self::$instance;
	}

	public static function shutdown() {
		if(!self::$instance) {
			throw new \Exception("Cannot invoke shutdown if the log was not init");
		}
		self::$instance->log(LOG_INFO, self::$instance->name." will shutdown");
		self::$instance=null;
		closelog();
	}

	// Do man syslog to learn about the level.
	public static function stlog($_level, $_msg) {
		if(!self::$instance) {
			throw new \Exception("Cannot invoke slog if the log was not init");
		}
		self::$instance->log($_level, $_msg);
	}
	
	// Do man syslog to learn about the level.
	public function log($_level, $_msg) {
		//TODO: Perhaps restrict level.
		//TODO: Perhaps restrict message lentgh, as syslog won't take more than 1024 bytes.
		syslog($_level, translate_phpsyslog_level($_level).' : '.$_msg);
	}

	private function __construct($_name, $_flags=LOGPID_OR_LOGODELAY, $_facility=LOG_LOCAL0) {

		$this->name=$_name;
		if(!openlog($this->name, $_flags, $_facility)) {
			throw new \Exception('Could not open log');
		}
	}

//Executing the destructor private destructor from the default context may generate warnings.
//	private function __destruct() {
//		closelog();
//	}
}
