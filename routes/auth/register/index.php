<?php
	require_once 'scripts/StatusCode.php';
    require_once 'S5.php';

	function request($params, $connection) {
	    
	  $security = new S5();
	  $status = new StatusCode();
	  
	  $registration = $security->register($params['username'], $params['password']);
	  
	  if($registration != false) {
	      $status->setCode(201);
	      $status->setMessage('User registered!');
	      $status->setData(['id' => $registration]);
	  } else {
	      $status->setCode(500);
	      $status->setMessage('Oops! That didn\'t work! Please try again later');
	  }
	
	  return $status->returnOutput();

	}
?>