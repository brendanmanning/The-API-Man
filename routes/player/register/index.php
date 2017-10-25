<?php
	function request($params, $connection) {
	
	  // Create an associative array for JSON output
	  $output = array("message" => "Hello, " . $params['name']);
	  
	  // Your code goes here...
	  // Consider using the $connection variable to access
	  // your database.
	  
	  // Return the associative array...
	  // API Man will turn it into a JSON object for you
	  return $output;
	}
?>