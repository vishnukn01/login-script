<?php

if(!class_exists('REGISTER')){
	
	class REGISTER{
		
		public function signUp($post){
			extract($post);
			//prepare
			global $db;
			$link = $db->connect();
			
			$stmt = $link->prepare("INSERT INTO users (fullname, username, email, password) VALUES (?,?,?,?)");
			$stmt->bind_param('ssss',$fullname, $username,$email, $password);
			$stmt->execute();
			if($stmt->affected_rows){
				
				$id = $stmt->insert_id;
				$password_updated = md5( md5($id).$password );
				$query = "UPDATE users SET password='$password_updated'
						  WHERE id=$id
						 ";
				$result = $link->query($query);
				
				if($result){				
					$_SESSION['id'] = $id;
					if($_POST['stayLoggedIn']){
						setcookie('id',$id, time()+60*60);
					}
					//header('Location: home.php');
					return true;
				}
				return false;
			}
			return false;
		}
	}
}
$register = new REGISTER;
?>