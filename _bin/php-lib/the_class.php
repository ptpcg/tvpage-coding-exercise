<?php

class PGCrawl{
	
	
	private $crawled = array();
	private $domains = array();
	private $links = array();
	private $last_crawl = array();
	private $crawl_tkn; //String
		
	function __construct($url,$user){
		$this->url = $url;	
		$this->user = $user;
		
		$url_bits = explode("/",$url);
		$domain = $url_bits[2];
		$protocol = $url_bits[0];
		
		$this->proto = $protocol;
		$this->domain = $domain;
		
	}
	
	function getURI(){
		return $this->url;
	}
	
	//get all links
	function getLinks($url,$r = null){
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
				
				//check if valid url & add to domain list if valid			   
			   if(filter_var($url, FILTER_VALIDATE_URL)){
				   	$url_bits = explode("/",$url);
					$domain = $url_bits[2];
					
					if(!in_array($domain,$this->domains)){
						$this->domains[] = $domain;
					}
					
				   	$urls["domains"][$domain]["urls"] = $url; 
				   	$this->links[$domain][] = $url;
				   	$urls["domains"][$domain]["count"]++;
			   }else{
			   		$urls["local_pgs"][] = $url;
			   }	
		   
		   	$this->crawled[] = $url;
		   }
		}
		if(!isset($r)){
			$this->last_crawl = $urls;
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
		$levels["domains"] = array();
		$levels["url"] = $this->url;
		
		//loop through the rest 
		while($levels2go > $level){
		
			$level++;
			foreach($links["local_pgs"] as $link){
				$link = $this->proto."//".$this->domain.$link;
				$links = $this->getLinks($link);
			}
			$levels["domains"] = $this->domains;
			$levels["links"] = $this->links;
		}
		
		$this->last_crawl = $levels;
		return $levels;
	}
	function saveCrawl(){
		$user = $this->user;
		$crawl_db = new Fllat("crawl_{$user}",_JSONDB);
		
		$time = date(_TIME_FORMAT); 
		
		$crawl = array(
			"user"=>$user,
			"time"=>$time,
			"url"=>$this->url,
			"link_count"=>count($this->last_crawl,COUNT_RECURSIVE),
			"depth"=>$this->last_crawl["MinusR"],
			"crawl_data"=>$this->last_crawl
		);
		
		$crawl_db->add($crawl);
	}
}
