<?php
	//
	//@param $action (String)
	//@param $token (String)
	//passed from ../../_jsdata/index.php
	//included in ""
	//
	
	switch($action){
		case "auth":
			$email = $_REQUEST["_email"];
			
			//validate email
			
			$pwd = $_REQUEST["_pwd"];
			
			//authenticate user
			$out = auth_user($email,$pwd,$user_db);
		break;
		case "reg":
			$email = $_REQUEST["_email"];
			
			//validate email
			
			$pwd = $_REQUEST["_pwd"];
			
			//register user
			
			$out = register_user($token,$email,$pwd,$user_db);
		break;
		case "get":
			
			$rtn = $_SESSION;
			$rtn["ok"] = true;
			
			$out = $rtn;
		break;
		case "logout":
			session_destroy();
			session_start();
			$token = gen_tkn();
			$_SESSION["tkn"] = $token;
			$rtn = $_SESSION;
			$rtn["ok"] = true;
			
			$out = $rtn;
		break;
		case "get_history":
			$crawl_db = new Fllat("crawl_{$token}",_JSONDB);
			$history = $crawl_db->select(array());
			if(count($history) < 1){

				$rtn = array(
					"data"=>array(array("url"=>"No Crawl History Yet","placeholder"=>true)),
					"ok"=>true
				);
			}else{
				
				$rtn = array(
					"data"=>array_reverse($history),
					"ok"=>true
				);
			}
			$out = $rtn;
		break;
		case "clear_history":
			$db_name = "crawl_{$token}";
			$crawl_db = new Fllat($db_name,_JSONDB);
			$rm = $crawl_db->del();
			
			if($rm == "Database '{$db_name}' successfully deleted."){
				$rtn = array(
					"ok"=>true,
					"rm"=>true
				);
			}else{
				$rtn = array(
					"fail"=>true,
					"res"=>$rm
				);
			}
			
			
			$out = $rtn;
		break;
	}
