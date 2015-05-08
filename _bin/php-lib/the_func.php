<?php

define("_SALT","70cdca07f68f52c2abf5301a29b774c4a83bd532743974db#$%$%now that's salty :P%%$%3b407d5382a2ec4e9fe0d5bef5c7ea87ee920fdd92c9b1be");


// Methods for security stuff

function gen_pwd($str,$salt = _SALT){
	$pwd = sha1($str.md5($str.$salt));
	return hash('haval192,5', $pwd);
}


function gen_tkn(){
	//
	//generates token string
	//
	//return (String) 
	
	$tkn = rand_str();
	$salt = gen_pwd(randStr(128));
	
	$token = gen_pwd($tkn,$salt);
	
	return $token;
}

function rand_str($len = 64) {

	//
	//generates random string
	//
	//@param $len (int) //desired length of string
	//
	//return (String) 
	
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_=';
    $chars_len = strlen($chars);
    $str = '';
    $c = $len;
    while($c > 0) {
        $str .= $chars[rand(0, $chars_len - 1)];
    	$c --;
    }
    return $str;
}

//Authentication & other user stuff

function register_user($tkn,$email,$pwd,$db){
	
	//
	//add new user to DB w/ email,tkn & passwd
	//
	//@param $tkn (String) //crawl/user token string
	//
	//@param $email (String) //email to check
	//
	//@param $pwd (String) //password string
	//
	//@param $db (Object) //Fllat DB Object
	//
	//return (Array) 
	
	$passwd = gen_pwd($pwd);
	
	
	if($db->exists("email",$email)){
		$rtn = array(
			"fail"=>true,
			"msg"=>"Email Already Registered"
		);
		
		return $rtn;
	}else{
		$user = array(
			"tkn"=>$tkn,
			"email"=>$email,
			"pwd"=>$passwd
		);
		
		$reg = $db->add($user)[0];
		
		if(isset($reg["tkn"])){
			
			$reg["ok"] = true;
			
			
		}
		unset($reg["pwd"]);
		return $reg;
	}
	
}

function auth_user($email,$pwd,$db){

	//
	//authenticates user against DB w/ email & passwd
	//
	//@param $email (String) //email to check
	//
	//@param $pwd (String) //password string
	//
	//@param $db (Object) //Fllat DB Object
	//
	//return (String) 
	
		
	$passwd = gen_pwd($pwd);
	
	$chk_pwd = $db->get("pwd","email",$email);
	
	if($passwd == $chk_pwd){
		$tkn = $db->get("tkn","email",$email);
		
		$rtn = array(
			"ok"=>true,
			"tkn"=>$tkn,
			"email"=>$email
		);
		
		//update session 
		session_start();
		
		$_SESSION["tkn"] = $tkn;
		$_SESSION["email"] = $email;
		
	}else{
		$rtn = array(
			"fail"=>true,
			"msg"=>"Email or Password Invalid"
		);
	}
	
	return $rtn;
		
}





