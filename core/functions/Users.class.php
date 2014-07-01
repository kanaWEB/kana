<?php

class User {

	protected $name,$right;
	function __construct(){
		$this->right = false;
	}

	function right(){
		return $this->right;
	}

	function name(){
		return $this->name;
	}

	//Verify if a account exists (returns false if not)
	function check_password($login,$password){
		$user = new Entity("Users");

		$user = $user->load([
			'name'=>$login,
			'password'=>sha1(md5($password))
			]);
		if($user){
		$this->right = $user->getState();
		$this->name = $user->getName();
		}
	}

	function check_session($currentUser){
		if(isset($currentUser)){
			$session = unserialize($currentUser);
			$this->right = $session->right();
			$this->name = $session->name();
		}
		else{
			return $this->right = false;
		}
	}



}


?>
