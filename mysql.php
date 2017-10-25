<?php
	function does_record_exist($table, $keyname, $key) {
		$conn = db();
		
		$sql = $conn->prepare("SELECT * FROM table WHERE :keyname = :key");
		$sql->bindParam(":keyname", $keyname);
		$sql->bindParam(":key", $key);
		$sql->execute();
		
		while($row = $sql->fetch()) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Update a certain of a player's database record
	 * @param uid The Player ID to update
	 * @param row The column to update. For example: "alive"
	 * @param value The new value to assign to the column. For example: false
	 */
	function update_user_row($uid, $column, $value) {
		
		$conn = db();
		
		$sql = $conn->prepare("UPDATE " . PLAYERS_TABLE . " :column=:value WHERE " . PLAYERS_TABLE_INDEX . "=:uid");
		$sql->bindParam(":column", $column);
		$sql->bindParam(":value", $value);
		$sql->bindParam(":uid", $uid);
		return $sql->execute();
		
	}