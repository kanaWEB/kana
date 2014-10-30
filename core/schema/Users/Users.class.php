<?php

class User {

	protected $id,$name,$right,$cookie,$default_view,$default_group;
	function __construct(){
		$this->right = false;
		$this->id = false;
		$this->name = false;
		$this->cookie = false;
		$this->default_view = false;
		$this->default_group = false;
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

	function default_view(){
		return $this->default_view;
	}

	function default_group(){
		return $this->default_group;
	}

	function RefreshDefaultView($view){
		$this->default_view = $view;
	}

	function RefreshDefaultGroup($group){
		$this->default_group = $group;
	}

	//Verify if a account exists (returns false if not)
	function check_password($login,$password,$cookie=false){
		$user = new Entity("Users");
		$user = $user->load([
			'name'=>$login,
			'password'=>sha1(md5($password))
			]);
		if(DEBUG){
		var_dump($user);
	}
		if($user){	
			$this->right = $user["state"];
			$this->name = $user["name"];
			$this->id = $user["id"];
			$this->default_view = $user["default_view"];
			$this->default_group = $user["default_group"];

			//If no cookie token exists, we generate it inside the database.
			//We destroy the cookie when we disconnect.
			if($cookie){	
				$expire_time = time() + COOKIE_LIFE*86400; //Day in seconds
				$actual_cookie = $user["cookie"];

				if ($actual_cookie == ""){
					$cookie_token = sha1(time().rand(0,1000));
					var_dump($user);
					$user_modified = new Entity("Users");
					$user_modified = Entity::data2object($user,$user_modified);
					$user_modified->Setcookie($cookie_token);
					$user_modified->save();

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
			$this->default_view = $session->default_view();
			$this->default_group = $session->default_group();
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
			$this->default_view = $user["default_view"];
			$this->default_group = $user["default_group"];
		}
	}

	function check_token($token){
		$tokens_db = new Entity("Tokens");
		$tokens = $tokens_db->load([
			"token" => $token
			]);
		if($tokens){
			$user = new Entity("Users");
			$user = $user->load([
				"id" => $tokens["id_user"]
				]);
			if($user){
			$this->right = $user["state"];
			$this->name = $user["name"];
			$this->id = $user["id"];
			$this->default_view = $user["default_view"];
			$this->default_group = $user["default_group"];
			}
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

	function viewsRight(){
		$views = Functions::getdir(USER_VIEWS);
		$view_right_db = new Entity("ViewRight");
		foreach($views as $key => $view){
			$view_right = $view_right_db->load([
			'id_user'=>$this->id,
			'id_view'=>$view
			]);
			if(!$view_right){
			unset($views[$key]);
			}
		}
	return $views;
	}

	function viewRight($view_name){
		$view_right_db = new Entity("ViewRight");
			$view_right = $view_right_db->load([
			'id_user'=>$this->id,
			'id_view'=>$view_name
			]);
		return $view_right;
	}

	function groupRight($group_id){
		$group_right_db = new Entity("GroupRight");
			$group_right = $group_right_db->load([
			'id_user'=>$this->id,
			'id_group'=>$group_id
			]);
		return $group_right;
	}


}


?>
