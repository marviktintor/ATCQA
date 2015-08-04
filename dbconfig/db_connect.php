<?php

class database{
	
	public function __construct(){
		require_once 'db_config.php';
		
	}
	
	public function open_database_connection($db_name=DB_SAAS){
		
		return new mysqli(DB_HOST, DB_USER, USER_PASSWORD, $db_name);
	}
	
	public function __destruct(){
		
	}
	
	
}

?>