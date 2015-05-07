<?php

class PGCrawl{
	
	
	private $crawled = array();
	private $domains = array();
	private $last_crawl = array();
	private $crawling = false;
		
	function __construct($url){
		$this->url = $url;	
	}
	
	function getURI(){
		return $this->url;
	}
	
	//get all links
	function getLinks($url,$r){
		//
		//Get links for given url
		//
		//@param $url(String)  //url to crawl
		//
		//@param $r(boolean)  //in recursive loop?
		//
		//return (Array)
	
		
		$url = isset($url)?$url:$this->url;
		
		$no_list = array(
			"javascript:void(0)",
			""
		);

		$html = file_get_contents($url);

		$dom = new DOMDocument();
		@$dom->loadHTML($html);

		// grab all the on the page
		$xp = new DOMXPath($dom);
		$links = $xp->evaluate("/html/body//a");

		for ($i = 0; $i < $links->length; $i++) {
		   $a = $links->item($i);
		   $url = $a->getAttribute('href');
		   if(!in_array($url,$no_list) && !in_array($url,$this->crawled)){
		   	$urls["links"] = $url;
		   	$this->crawled[] = $url;
		   }
		}
		if(!isset($r)){
			$this->$last_crawl = $urls;
		}
		return $urls;
	}

	function getLinksMinusR($rlevel){
		//
		//Get links recursively
		//
		//@param $rlevel(int)  //levels to crawl recursively
		//
		//return (Array)
	
		$levels2go = $rlevel;
		$level = 0;
		$levels = array();
	
		//get top level urls
		$links = $this->getLinks();
		$levels["MinusR"] = $rlevel;
		$levels[0][$this->url] = $links;	
	
		while($levels2go > $level){
		
			$level++;
			foreach($links as $link){
				$links = $this->getLinks($link,true);
				$levels[$level][$link] = $links;	
			}
		}
		
		$this->$last_crawl = $levels;
		return $levels;
	}
	
}
