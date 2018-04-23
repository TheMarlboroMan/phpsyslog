<?php
//This should load the PHP5 or 4 version, along with the needed free functions.
require("src/phpsyslog.php");

//The recommendation here is to use the free functions whenever there is a
//chance code will run in either PHP4 or 5. Free functions are easily read and
//located, but they will eat additional cycles in background work and will
//force us to call them in order. Unlike the OO interface, the free functions 
//will make their best to avoid exposing exceptions, making them usable in 
//both PHP versions.

//First we must init the log so we can set the app name and locate it
//in our log files. This will use the default flags
phpsyslog_init("myapp");

//Next we can do a quick calls to qlog.
qlog(LOG_INFO, "Using qlog...");

//And finally we can shutdown if we want.
phpsyslog_shutdown();


//But we can also use the OO interface, which opens up a lot of possibilities
//and headaches if we are using PHP4... The recommendation here is to use the
//OO interface for PHP5 as it is orderly and safe. If we gotta use PHP4, please,
//stick to this interface too.

//First let us init the class and use the default flags.
phpsyslog::init("myapp");

//Now we can log in a number of ways... the most verbose is:
phpsyslog::get()->log(LOG_INFO, "This is the most verbose way of writing log info");

//Now, we can get a reference to the logger instance and use it.
$logger=phpsyslog::get();
$logger->log(LOG_INFO, "This is the logger way");
unset($logger);

//There is a quick static way too...
phpsyslog::stlog(LOG_INFO, "This is the static way of writing log info");

//When we are done we can (it is optional) close the logger.
phpsyslog::shutdown();

//One final note: after cleanup or whithout init the app name is lost.
//Qlog will set up a default logger if invoked but dood luck finding it in your 
//system logs... In this case you can always change the system configuration
//of your syslogd so get_default_phpsyslog_name() redirects to the
//correct files. The value returned by get_default_phpsyslog_name()
//is "phpsyslog".

qlog(LOG_INFO, "qlog using the default name, which should be ".get_default_phpsyslog_name());
