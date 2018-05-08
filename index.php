<?php
session_start();
require_once('header.php');
require_once('load.php');

$time = time();
$action = 'login_form';
$str = sprintf('%s_%s_%s', $time, $action, NONCE);
$hash = hash('sha512', $str);

if(array_key_exists('id', $_SESSION)){
	header('Location: home.php');
}

if(isset($_GET['loggedOut'])){
	session_destroy();
	setcookie('id','',time()-60*60);
	$_COOKIE['id'] = '';
}

if($_POST){	
	
	if(check_form($_POST)==true){
		
		$args = array(
			'email'=>'FILTER_VALIDATE_EMAIL',
			'password'=>'FILTER_SANITIZE_STRING'
		);
		$post = filter_var_array($_POST, $args);
		if($post){
		
			if($login->login_check($post)){
				header('Location: home.php');
			}else{	
				$error = 'Invalid email and password combination';
			}
		}
		else{
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
		<input type="email" class="form-control" id="email" name="email" placeholder='Email id' required>
	  </div>
	  <div class="form-group">
		<input type="password" class="form-control" id="password" name="password" placeholder='Password' required>
	  </div>
	  <div class="form-group form-check">
		<label class="form-check-label">
		  <input class="form-check-input" type="checkbox" name='stayLoggedIn' value='1'> Stay logged in
		</label>
	  </div>
	  <button type="submit" class="btn btn-primary">Log in</button>
	</form>
	<a href='register.php' class='toggle_forms'>Register here</a>
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