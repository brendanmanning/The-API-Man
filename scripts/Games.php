<?php
  require_once 'scripts/ORM.php';
  
  function createGame($data) {
      
      $game = R::dispense('games');
      
      $game->name = $data['name'];
      
      return R::store($game);
  }
  
  function getGame($id) {
      return R::load('games', $id);
  }    