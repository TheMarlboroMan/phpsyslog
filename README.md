# phpsyslog
OO and free function interfaces for syslog.

## Archiving

As of September 2022 this project is archived and will be developed no longer. Consider using a logger class if need be.

## Why?

It used to be that you would go over UDP in port 514 to talk to the syslog daemon. Then the people on the SUS came up with openlog(), closelog() and syslog() and everything became easier.

So... Why should you prefer:

	phpsyslog_init("app_name");
	qlog(LOG_INFO, "Log");
	qlog(LOG_INFO, "Another");
	phpsyslog_shutdown();

... or even ...

	phpsyslog::init("app_name");
	phpsyslog::get()->log(LOG_INFO, "log");
	phpsyslog::stlog(LOG_INFO, "Another");
	phpsyslog::shutdown();

... to good old ...

	openlog("app_name", LOG_PID | LOG_ODELAY, LOG_LOCAL0);
	syslog(LOG_INFO, "Log");
	syslog(LOG_INFO, "Another");
	closelog();

... ??

Well, maybe you shouldn't. Personally I would use it because of the following advantages:

	- Auto formatting of log levels.
	- Code safety in PHP5 and higher.
	- Non-conformism to the tyrants at the PSR-3 (just joking).
	- Future extensibility, as this project is - more than anything - a logger interface: perhaps you'll want to extend it with database logging, remote file logging, /dev/null redirecting...

Perhaps the right question is... do I really need 100 lines of a logger class and helper functions when I can use the built-in ones?. I'd personally say that you don't *need* it, but could *benefit* of it (for the aforementioned reasons). Also, if you were to strip the comments, white lines and formatting quirks you could get the same funcionality in less than 50 lines ;).

## Ok. How do I use this?.

1) Include the src/phpsyslog.php file: it will load the PHP4/5 version along with common helper(s). 
2) For the easy way, read main.php. For the hard way, take a look at the class and functions in src/phpsyslog5.php.
3) Log away to your heart's contents.

If you need information about logging levels and flags do a "man syslog" :).

## Where are my logs?.

To make this work don't forget to:

- Add/check the line that pulls your to your log file in the syslog config file, like:
	[facilityname]			/var/log/myapp.log

- Optionally mute it in the general system log (talk to your sysadmin).
- Restart the service (sudo service rsyslog restart will do).
- Specify an app name when you init the log, in case you can resort to advanced filtering.
