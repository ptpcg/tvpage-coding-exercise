<?php
//	
	require_once("./config.php");
//	require_once(_PHPLIB."the_func.php");
	
	$template = file_get_contents(_VIEWDIR."template.html");
	
//	$render = str_replace("",_,$template);
	
	header("Content-Type:text/html");
	exit($template);
?>
