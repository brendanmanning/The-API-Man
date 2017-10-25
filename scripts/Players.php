<?php
    
    require_once 'scripts/Constants.php';
    require_once 'scripts/ORM.php';
    
    function createPlayer($data) {
      $player = R::dispense(players_table);
      
      $player->name = data['name'];
      $player->game = data['game']->id;
      $player->s5id = data['s5id'];
      $player->alive = true;
      
      return R::store($player);
      
    }
    
    function getPlayer($id) {
        return R::load(players_table, $id);
    }
?>
    