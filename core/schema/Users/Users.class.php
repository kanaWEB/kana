<?php

class User {

	protected $id,$name,$right;
	function __construct(){
		$this->right = false;
		$this->id = false;
		$this->name = false;
	}

	function right(){
		return $this->right;
	}

	function name(){
		return $this->name;
	}

	function id(){
		return $this->id;
	}

	//Verify if a account exists (returns false if not)
	function check_password($login,$password,$cookie=false){
		$user = new Entity("Users");
		$user = $user->load([
			'name'=>$login,
			'password'=>sha1(md5($password))
			]);
		if($user){
			$this->right = $user["state"];
			$this->name = $user["name"];
			$this->id = $user["id"];

			//If no cookie token exists, we generate it inside the database.
			//We destroy the cookie when we disconnect.
			if($cookie){	
				$expire_time = time() + COOKIE_LIFE*86400; //Day in seconds
				$actual_cookie = $user["cookie"];

				if ($actual_cookie == ""){
					$cookie_token = sha1(time().rand(0,1000));
					$user->setCookie($cookie_token);
					$user->save();
				}
				else{
					$cookie_token = $actual_cookie;
				}	
				Functions::makeCookie(COOKIE_NAME,$cookie_token,$expire_time);
			}
		}
	}

	//Check if a user is connected
	function check_session($currentUser){
		if(isset($currentUser)){
			$session = unserialize($currentUser);
			$this->right = $session->right();
			$this->name = $session->name();
			$this->id = $session->id();
		}
		else{
			return $this->right = false;
		}
	}

	//Check cookie
	function check_cookie($cookie){
		if(isset($cookie)){
			$user = new Entity("Users");
			
			$user = $user->load([
				"cookie" => $cookie
				]);
		}

		if($user){
			$this->right = $user["state"];
			$this->name = $user["name"];
			$this->id = $user["id"];
		}
	}

	function isuser(){
		if($this->right > 0){
			return true;
		}
		else
			return false;
	}

	function isadmin(){
		if($this->right == 1){
			return true;
		}
		else
			return false;
	}



}


?>
