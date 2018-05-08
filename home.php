<?php
@session_start();
require_once('header.php');
require_once('includes/class-db.php');

if(!array_key_exists('id', $_SESSION)){
	header('Location: index.php');
}

?>

<div class='container'>

	<div class='well'>
	
		<h2>Welcome
		
		<?php
			$link = $db->connect();
			if(array_key_exists('id', $_SESSION)){
			
				$q = "
						SELECT * FROM users
						WHERE id=$_SESSION[id]
					 ";
				$result = $link->query($q);
				$row = $result->fetch_array();
				echo "<h2>".htmlspecialchars($row['fullname'], ENT_QUOTES)."</h2>";
			
			}
		?>
		<h3><a href='index.php?loggedOut=1'>Log out</a></h3>
		</h2>
	</div>

</div>

<?php
require_once('footer.php');

?>