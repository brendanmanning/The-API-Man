<?php
	require 'scripts/Games.php';
	require 'scripts/Round.php';
	require 'scripts/StatusCode.php';
	
	function request($params, $connection) {
        createRound(getGame(createGame(['name' => $params['name']])));
        
        $status = new StatusCode(200, 'Round Created');
        $status->output();
	}
?>