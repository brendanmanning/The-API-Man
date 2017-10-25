<?php
	$DB_HOST = "localhost";
	$DB_USER = "brenuzrr_wgwars";
	$DB_PASS = "12265790";
	$DB_NAME = "brenuzrr_wgwars";
	
	function db() {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	 
		return $conn;	
	}
	
	define("PLAYERS_TABLE", "players");
	define("PLAYERS_TABLE_INDEX", "playerid");
	
?>