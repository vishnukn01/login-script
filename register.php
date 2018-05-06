<?php
session_start();
require_once('header.php');
require_once('functions.php');
require_once('class-db.php');

if(array_key_exists('id', $_SESSION)){
	header('Location: home.php');
}

$time = time();
$action = 'register_form';
$str = sprintf('%s_%s_%s', $time, $action, NONCE);
$hash = hash('sha512',$str);

if($_POST){
	
	if(check_form($_POST)){
		
		$args = array(
		
			'fullname'=>'FILTER_SANITIZE_STRING',
			'username'=>'FILTER_SANITIZE_STRING',
			'email'=>'FILTER_VALIDATE_EMAIL',
			'password'=>'FILTER_SANITIZE_STRING'
		
		);
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			$error = 'Inavlid email address';
		}else{
			
			$post = filter_var_array($_POST, $args);
		if($post){
			extract($post);
			//prepare
			
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
					header('Location: home.php');
				}	
			}
		}
			
		}
		
		
	}else{
	
		$error = 'Error submitting the form.';
	}
}

?>

<div class='container'>

	<form action="" method="post">
	  <h3>Register</h3>

	  <div class="form-group">
		<input type='hidden' name='timestamp' value='<?php echo $time; ?>'>
		<input type='hidden' name='form_action' value='<?php echo $action; ?>'>
		<input type='hidden' name='form_hash' value='<?php echo $hash; ?>'>
		<input type="text" class="form-control" id="fullname" name='fullname' placeholder='Full name' required>	
	  </div>
	  <div class="form-group">
		<input type="text" class="form-control" id="username" name='username' placeholder='Username' required>
	  </div>
	  <div class="form-group">
		<input type="email" class="form-control" id="email" name="email" placeholder='Email id' required>
	  </div>
	  <div class="form-group">
		<input type="password" class="form-control" id="password" name="password" placeholder='Password' required>
	  </div>
	  <div class="form-group form-check">
		<label class="form-check-label">
		  <input class="form-check-input" type="checkbox"> Stay logged in
		</label>
	  </div>
	  <button type="submit" class="btn btn-primary">Register</button>
	</form>
	<a  href='login.php'>Have an account? Log in</a>
	<div class='error_box'>
		<?php
			if(isset($success)){
				echo 'Success';
			}
			if(isset($error)){
				echo "
						<div class=\"alert alert-danger\" role=\"alert\">
						  <b>Error: </b>".$error."
						</div>
					 ";
			}
		?>
	</div>
</div>



<?php
require_once('footer.php');

?>