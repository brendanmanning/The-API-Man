<?php

  require_once 'config.php';
  require_once 'mysql.php';
  
  /*
   ***** Database Structure for Player object *****
   | uid (int) | 

  class Player {
  
  private $conn = db();
  
  private $uid = null;
  private $profile = null;
  private $targets = [];
  private $alive = false;
  
   /**
    * Constructor for objects of the Player class
    * @param uid The unique identifier for a user in the database. For example a Firebase ID. If the user does not exist upon instantiation, the user will be created with a blank profile.
    */
   function __construct($uid) {
     $this->uid = $uid;
    	
     // Does this user already exist?
     $exists = does_record_exist("players", PLAYERS_TABLE);
    	
     if(!$exists || $uid < 0) {
       $this->create_new_player();
     } else if($exists) {
       $this->build_object();
     }
   }
   
   /**
    * Builds this object from a SQL query
    */
   private function build_object() {
   
     // Fetch the properties
     $sql = $this->conn->prepare("SELECT * FROM " . PLAYERS_TABLE . " WHERE " . PLAYERS_TABLE_INDEX . " LIKE :id");
     $sql->bindParam(":id", $this->uid);
     $sql->execute();
     
     // Bind the properties
     while($row = $sql->fetch()) {
     	$this->alive = $row['alive'];
     	$this->name = $row['name'];
     }
   }
   
   /**
    * Creates a new Player
    */
    private function create_new_player() {
    	
    	// Create a new player, using the information encoded in the $uid variable
    	$sql = $this->conn->prepare("INSERT INTO " . PLAYERS_TABLE . "(" . PLAYERS_TABLE_INDEX . ",profile) VALUES (:index, :profile)");
    	$sql->bindParam(":index", $this->uid);
    	$sql->bindParam(":profile", '{}');
    	$sql->execute();

    }
    
    /**
     * Call when this player kills another player
     * @param killed The player that this Player killed.
     */
    public function kill($player) {
    
    }
  }
 ?>