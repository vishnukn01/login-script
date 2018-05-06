<?php

require_once('config.php');

if(!class_exists('DB')){
	
	class DB{
		
		public function connect(){
			
			return $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); 
		}
		
	}
}

$db = new DB;


?>