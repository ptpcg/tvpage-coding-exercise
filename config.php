<?php
	
//App Config

	define("_TIME_FORMAT","D M j, Y G:i:s T"); //time display format

	define("_LOC", __DIR__); //app root dir
	define("_BINDIR",_LOC."/_bin/"); //external bin dir (should be outside of web root if possible)
	define("_PHPLIB",_BINDIR."php-lib/"); //php libs dir
	define("_JSDATA",_BINDIR."js-data/"); //
	define("_VIEWDIR",_BINDIR."views/"); //html dir
	
	//Database
	define("_DB",_PHPLIB."fllat/fllat.php"); //fllat class location
	define("_JSONDB",_BINDIR."_jsonDB");	 //fllat JSON DB location
