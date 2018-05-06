<?php
session_start();
require_once('header.php');
require_once('functions.php');
require_once('class-db.php');
$time = time();
$action = 'login_form';
$str = sprintf('%s_%s_%s', $time, $action, NONCE);
$hash = hash('sha512', $str);

if(array_key_exists('id', $_SESSION)){
	header('Location: home.php');
}

if(isset($_GET['loggedOut'])){
	session_destroy();
}


if($_POST){
	
	if(check_form($_POST)==true){
		
		//sanitize
		$args = array(
			'email'=>'FILTER_VALIDATE_EMAIL',
			'password'=>'FILTER_SANITIZE_STRING'
		);
		$post = filter_var_array($_POST, $args);
		if($post){
			extract($post);
			$link = $db->connect();
			$email = mysqli_real_escape_string($link, $email);
			$result = $link->query("SELECT * FROM users WHERE email='$email' LIMIT 1");
			$row = $result->fetch_assoc();
			if($row['password'] == md5( md5($row['id']).$password ) ){
				
				$_SESSION['id'] = $row['id'];
				header('Location: home.php');
			}
			else{
				$error = 'Invalid email and password combination';
			}	
		}else{
			$error = 'Invalid characters entered';
		}	
	}else{
		$error = 'Failed to submit form. Please try again.';
	}

}

?>

<div class='container'>

	<form action="" method="post">
	  <h3>Log in</h3>
	  <input type='hidden' name='timestamp' value='<?php echo $time; ?>'>
	  <input type='hidden' name='form_action' value='<?php echo $action; ?>'>
	  <input type='hidden' name='form_hash' value='<?php echo $hash; ?>'>
	  <div class="form-group">
		<input type="email" class="form-control" id="email" name="email" placeholder='Email id'>
	  </div>
	  <div class="form-group">
		<input type="password" class="form-control" id="password" name="password" placeholder='Password'>
	  </div>
	  <div class="form-group form-check">
		<label class="form-check-label">
		  <input class="form-check-input" type="checkbox"> Stay logged in
		</label>
	  </div>
	  <button type="submit" class="btn btn-primary">Log in</button>
	</form>
	<a href='register.php'>Register here</a>
	<div class='error_box'>
	<?php
	if(isset($error)){
		echo "
				<div class=\"alert alert-danger\" role=\"alert\">
					<b>Error:</b> ".$error."
				</div>
			 ";
	}
	?>
	</div>
</div>


<?php

require_once('footer.php');

?>