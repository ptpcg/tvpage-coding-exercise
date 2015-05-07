<?php

	include("./_bin/php-lib/the_class.php");
	
	header("Content-Type:application/json");
	
	$crawler = new PGCrawl("http://appblokes.com");
	
	die(json_encode($crawler->getLinksMinusR(2),JSON_PRETTY_PRINT));
	
	
?>
