<?php

if(!class_exists('LOGIN')){
	
	class LOGIN{
		
		public function login_check($post){
			global $db;
			extract($post);
			$link = $db->connect();
			$email = mysqli_real_escape_string($link, $email);
			$result = $link->query("SELECT * FROM users WHERE email='$email' LIMIT 1");
			$row = $result->fetch_assoc();
			if($row['password'] == md5( md5($row['id']).$password ) ){
				
				$_SESSION['id'] = $row['id'];
				if($_POST['stayLoggedIn']){
					setcookie('id',$row['id'], time()+60*60);
				}
				return true;
			}
			return false;
		}	
	}
}

$login = new LOGIN;
?>