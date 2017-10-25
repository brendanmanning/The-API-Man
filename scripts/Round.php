<?php

require_once 'scripts/Constants.php';
require_once 'scripts/ORM.php';

/**
 * Create a new round.
 */
function createRound($game)
{
    
    // Create the rounds tabe if it does not yet exist
    $round = R::dispense(rounds_table);
    
    // End any rounds the game already has
    foreach ($game->rounds as $round) {
        if ($round->finished != 0) {
            $round->finish();
        }
    }
    
    $round->gameid               = $game->id;
    $round->start                = time();
    $round->initial_player_count = 0;
    $round->final_player_count   = -1;
    
    $roundid = R::store($round);
    
}

/**
 * Get a round from the database
 */
function getRound($id)
{
    return R::load(rounds_table, $id);
}

?>    