<?php
	include("../config.php");

	include(_PHPLIB."the_class.php");
	include(_PHPLIB."the_func.php");
	include(_BINDIR."db.php");
	
	header("Content-Type:application/json");
	
	$section = $_REQUEST["sect"];
	$action = $_REQUEST["act"];
	
	session_start();
	if(!isset($_SESSION["tkn"])){
		$token = gen_tkn();
		$_SESSION["tkn"] = $token;

	}else{
		$token = $_SESSION["tkn"];
	}
	
	
	switch($section){
		case "crawl":
			$url = $_REQUEST["url"]; //url to crawl
			$mR = $_REQUEST["minusR"]; //recursion level
			$tkn = $token;
			
			$crawler = new PGCrawl($url,$tkn);
			
			$out = array(
				"ok"=>true,
				"data"=>$crawler->getLinksMinusR($mR),
				"tkn"=>$tkn
			);
			
			$crawler->saveCrawl();
			
		break;
		case "user":
			include(_JSDATA."user.php");
		break;
	}
	
			
	die(json_encode($out,JSON_PRETTY_PRINT));
