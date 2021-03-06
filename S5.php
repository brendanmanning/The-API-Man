<?php
  //
  // S5.php
  // Super Simple Server Side Security
  // User authentication and API backend made easy
  // Available from: https://github.com/brendanmanning/S5/
  //
  // (c) 2017 Brendan Manning
  // MIT License
  //
  class S5 {
    // Your database connection information here
    private $database_connection = [
      "db_host" => "{DB_HOST}",
      "db_name" => "{DB_NAME}",
      "db_user" => "{DB_USER}",
      "db_pass" => "{DB_PASS}",
      "db_users_table" => "users", // Table where your user accounts are saved. Created if not exists
      "db_tokens_table" => "tokens", // Table where user tokens will be saved. Created if not exists
      "db_api_table" => "api", // Table where api keys and tokens will be saved. Created if not exists
      "db_permissions_table" => "permissions", // Table where metadata about permissions will be saved
      "db_permission_assignments_table" => "permissionassignments"
    ];
    // Your configuration options here
    private $configuration = [
      "hash_algorithm" => PASSWORD_BCRYPT,
      "hash_options" => [
        'cost' => 10
      ],
      "api" => [
        "key_length" => 25,
        "secret_length" => 25
      ],
      "token_length" => 30,
      "token_expiration_seconds" => 31536000,
      "username_requirements" => [
        "length" => 3
      ],
      "password_strength" => [
        "length" => 4,
        "lowercase" => 0,
        "uppercase" => 0,
        "numerals" => 0,
        "specials" => 0
      ]
    ];
    // MySQL PDO connection - will be generated by the constructor
    private $conn = null;
    // The last error Message
    private $errors = [];
    public function __construct() {
      // Create a connection to the database
      $this->conn = new PDO(
        "mysql:host=" . $this->database_connection['db_host'] .
        ";dbname=" . $this->database_connection['db_name'],
        $this->database_connection['db_user'],
        $this->database_connection['db_pass']
      );
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    // Signup function - Adds a user to the database
    public function register($username, $password) {
      // Make sure the usernam is valid and uniqueness
      if(!$this->verify_username_validity($username)) {
        return false;
      }
      // Make sure the password is valid
      if(!$this->verify_password_strength($password)) {
        return false;
      }
      // Hash the user's password using bcrypt
      // PHP will automatically salt it for us
      $hashedPassword = password_hash($password, $this->configuration['hash_algorithm'], $this->configuration['hash_options']);
      // Prepare to Save the username, password, and salt in the database
      $sql = $this->conn->prepare("INSERT INTO " . $this->database_connection['db_users_table'] . " (user,password) VALUES (:user,:password)");
      $sql->bindParam(":user", $username);
      $sql->bindParam(":password", $hashedPassword);
      $success = $sql->execute();
      
      if($success) {
        return $this->conn->lastInsertId();
      } else {
        false;
      }
    }
    // Login function - Attempts to login a user given their username and password
    public function login($username, $password) {
      // Make sure the account exists
      if(!$this->verify_username_exists($username)) {
        return false;
      }
      // Make sure the account is active
      if(!$this->verify_account_active($username)) {
        return false;
      }
      // Select the password from the database
      $sql = $this->conn->prepare("SELECT id, password FROM " . $this->database_connection['db_users_table'] . " WHERE user LIKE :user");
      $sql->bindParam(":user", $username);
      // Execute the query and verify it's results
      $success = $sql->execute();
      if(!$success) {
        return false;
      }
      
      // Get the fetched row while making sure only one result was returned
      $userid = -1;
      $passwordHash = null;
      while( $row = $sql->fetch() ) {
        // A row had already been returned (not good!)
        if($passwordHash != null) {
          return false;
        }
        // Retrieve the hash
        $passwordHash = $row['password'];
        $userid = $row['id'];
      }
      // Make sure that we got ANYTHING at all
      if($passwordHash == null) {
        return false;
      }
      // Use PHP's password_verify to see if the password is correct
      $loggedin = password_verify($password, $passwordHash);
      if($loggedin) {
        return $userid;
      } else {
        return false;
      }
    }
    
    /**
     * Create a permission
     * @param name The name of the permission. For example "posting"
     * @param description The description of the permission. For example "Users with this permission may add posts"
     * @return The id of the permission on success, false on error
     */
    function create_permission($name, $description) {
      $sql = $this->conn->prepare("INSERT INTO " . $this->database_connection['db_permissions_table'] . " (permissionname, permissiondescription) VALUES (:name,:description)");
      $sql->bindParam(":name", $name);
      $sql->bindParam(":description", $description);
      if($sql->execute()) {
        return $this->conn->lastInsertId();
      } else {
        return false;
      }
    }
     
    /**
     * Delete a permission
     * @param $permissionid The database ID of the permission to delete
     * @return True/False based on the success of the operation
     */
    function delete_permission($permissionid) {
      $sql = $this->conn->prepare("DELETE FROM " . $this->database_connection['db_permissions_table'] . " WHERE permissionid = :id");
      $sql->bindParam(":id", $permissionid);
      return $sql->execute();
    }
    
    /**
     * Get a permission
     * @param $permissionid The database ID of the permission to fetch
     * @return The permission as an associative array or NULL on error
     */
    function get_permission($permissionid) {
      $permission = array();
      $error = true;
      
      $sql = $this->conn->prepare("SELECT * FROM " . $this->database_connection['db_permissions_table'] . " WHERE permissionid=:id");
      $sql->bindParam(":id", $permissionid);
      $sql->execute();
      
      while($row=$sql->fetch()) {
        $permission["id"] = $row['permissionid'];
        $permission["name"] = $row['permissionname'];
        $permission["description"] = $row['permissiondescription'];
        $permission['createdAt'] = $row['created'];
        
        $error = false;
      }
      
      if($error) {
        return NULL;
      } else {
       return $permission;
      }
    }
    
    /**
     * List all permissions
     * @return An array of permissions. Each permission is in the form of an associative array
     */
    function list_permissions() {
     
      $permissions = array();
    
      $sql = $this->conn->prepare("SELECT * FROM " . $this->database_connection['db_permissions_table']);
      $sql->execute();
      
      while($row=$sql->fetch()) {
        $permissions[] = array(
          "id" => $row['permissionid'],
          "name" => $row['permissionname'],
          "description" => $row['permissiondescription'],
          "createdAt" => $row['created']
        );
      }
      
      return $permissions;
    }
    
    /**
     * List all permissions on a user
     * @param $userid The user to list permission for
     * @return An array of permissions that have been granted to the user. Each permission is in the form of an associative array
     */
    function list_permissions_on($userid) {
    
      $permissions = array();
      
      $sql = $this->conn->prepare("SELECT permissionid FROM " . $this->database_connection['db_permission_assignments_table'] . " WHERE userid=:user AND enabled=1");
      $sql->bindParam(":user", $userid);
      $sql->execute();
      
      while($row = $sql->fetch()) {
        $permissions[] = $this->get_permission($row['permissionid']);
      }
      
      return $permissions;
    }
     
    /**
     * Check if a user is granted a certain permission
     * @param $userid The user to check for
     * @param $permissionid The permission to check for
     * @return True/False based on whether or not the user has permission
     */
    function has_permission($userid, $permissionid) {
      $granted = false;
      
      $sql = $this->conn->prepare("SELECT granted FROM " . $this->database_connection['db_permission_assignments_table'] . " WHERE userid=:user AND permissionid=:id AND enabled=1");
      $sql->bindParam(":user", $userid);
      $sql->bindParam(":id", $permissionid);
      $sql->execute();
      
      while($row = $sql->fetch()) {
        // We found something! The user has permission!
        return true;
      }
      
      return false;
    }
     
    /**
     * Grant a user a permission
     * @param $userid The user to give the permission to
     * @param $permissionid The permission to give to the user
     * @return True/False based on the success of the operation
     */
    function grant_permission($userid, $permissionid) {
      $sql = $this->conn->prepare("INSERT INTO " . $this->database_connection['db_permission_assignments_table'] . " (permissionid, userid) VALUES (:permissionid, :userid)");
      $sql->bindParam(":permissionid", $permissionid);
      $sql->bindParam(":userid", $userid);
      return $sql->execute();
    } 
     
    /**
     * Remove a permissions from a user
     * @param $userid The user to remove the permission from
     * @param $permissionid The permission to remove
     * @return True/False based on the success of the operation
     */
    function remove_permission($userid, $permissionid) {
      $sql = $this->conn->prepare("DELETE FROM " . $this->database_connection['db_permission_assignments_table'] . " WHERE permissionid=:permissionid AND userid=:userid");
      $sql->bindParam(":permissionid", $permissionid);
      $sql->bindParam(":userid", $userid);
      return $sql->execute();
    }
    
    // Toggle's a user's active status
    public function set_user_active_status($username, $status) {
      // Make sure the user exists
      if(!$this->verify_username_exists($username)) {
        $this->log_error("User \"" . $username . "\" does not exist");
        return false;
      }
      // Run a query to update their active property
      $sql = $this->conn->prepare("UPDATE " . $this->database_connection['db_users_table'] . " SET active=:active WHERE user LIKE :user");
      $sql->bindParam(":active", $status);
      $sql->bindParam(":user", $username);
      // Return whether it works or not
      return $sql->execute();
    }
    // Utility function that uses the above to enable an account
    public function set_user_active($username) {
      return $this->set_user_active_status($username, 1);
    }
    // Utility function that uses the above to disable an account
    public function set_user_inactive($username) {
       return $this->set_user_active_status($username, 0);
    }
    // Token creation function
    public function create_token($username) {
      // Make sure that user exists
      if(!$this->verify_username_exists($username)) {
        $this->log_error("User \"" . $username . "\" does not exist");
        return false;
      }
      // Create a truly random string for our token
      $token = bin2hex(random_bytes($this->configuration['token_length']));
      // Prepare to insert it into the tokens table
      $sql = $this->conn->prepare("INSERT INTO " . $this->database_connection['db_tokens_table'] . " (user,token,expiration) VALUES (:user,:token, :expiration)");
      $sql->bindParam(":user", $username);
      $sql->bindParam(":token", $token);
      // Get the current time (in seconds since the epoch) and add the token validity length to it
      $expirationSeconds = time() + $this->configuration['token_expiration_seconds'];
      $sql->bindparam(":expiration", $expirationSeconds);
      // Run the query
      $success = $sql->execute();
      // If the query worked, return the token
      if($success) {
        return [
          'token' => $token,
          'expiration' => $expirationSeconds
          ];
      }
      // If the query failed, return false
      else {
        return false;
      }
    }
    // Token validation function
    public function verify_token($username, $token) {
      // Prepare a query to select any rows from the database with this user AND this token
      $sql = $this->conn->prepare("SELECT expiration FROM " . $this->database_connection['db_tokens_table'] . " WHERE user LIKE :user AND token LIKE :token");
      $sql->bindParam(":user", $username);
      $sql->bindParam(":token", $token);
      // Execute the query and make sure it works
      $success = $sql->execute();
      if(!$success) {
        return false;
      }
      // Get the expiration date of the token ... making sure no more than one result was returned
      $expirationSeconds = null;
      while( $row = $sql->fetch() ) {
        // Make sure we haven't already gotten something (there's duplicates in our table)
        if($expirationSeconds != null) {
          return false;
        }
        // Save the expiration date in the form of seconds since the epoch
        $expirationSeconds = $row['expiration'];
      }
      // Make sure a value was fetched
      if($expirationSeconds == null) {
        $this->log_error("No results for token \"" . $token . "\" assigned to user \"" . $username . "\"");
        return false;
      }
      // Now just make sure the current time is less then the expiration time
      $tokenValid = ( time() < $expirationSeconds );
      // Log if the token expired
      if(!$tokenValid) {
        $this->log_error("The token has expired");
        return false;
      }
      return true;
    }
    // API Credentials Creator
    public function create_api_credentials() {
      // Generate a random API key & secret
      $API_KEY = bin2hex(random_bytes($this->configuration["api"]["key_length"]));
      $API_SECRET = bin2hex(random_bytes($this->configuration["api"]["secret_length"]));
      // Prepare a query to insert them into the database
      $sql = $this->conn->prepare("INSERT INTO " . $this->database_connection['db_api_table'] . " (api_key,api_secret) VALUES (:key,:secret)");
      $sql->bindParam(":key", $API_KEY);
      $sql->bindParam(":secret", $API_SECRET);
      // Execute the query
      $success = $sql->execute();
      // If it fails, return false
      if(!$success) {
        return false;
      }
      // If we made it here, it worked! Return an indexed array with the key and secret
      return [
        "key" => $API_KEY,
        "secret" => $API_SECRET
      ];
    }
    // API Credentials Verifier
    public function verify_api_credentials($key,$secret) {
      // Prepare a query to select any matched for the key and secret
      $sql = $this->conn->prepare("SELECT * FROM " . $this->database_connection['db_api_table'] . " WHERE api_key LIKE :key AND api_secret LIKE :secret");
      $sql->bindParam(":key", $key);
      $sql->bindParam(":secret", $secret);
      // Execute the query
      $success = $sql->execute();
      // Make sure exactly one result was returned
      return $this->exactly_one_result_returned($sql);
    }
    // Helper for the above when working with $_GET
    public function verify_get_api_credentials() {
      // Make sure an API key and secret are given
      if(!isset($_GET['api_key']) || !isset($_GET['api_secret'])) {
        return false;
      }
      // Use the function above with the get values
      return $this->verify_api_credentials($_GET['api_key'], $_GET['api_secret']);
    }
    // Verifies an API request with the given parameters
    public function verify_api_request($api_key, $api_secret, $user, $token) {
      // Verify the API credentials
      if(!$this->verify_api_credentials($api_key, $api_secret)) {
        return false;
      }
      // Verify the user's token
      if(!$this->verify_token($user, $token)) {
        return false;
      }
      // If we've made it to this point, we're all good!
      return true;
    }
    // Helper function that Verifies any API request, filling in the needed parameters from the $_GET array
    public function verify_get_api_request() {
      // The following api properties should be part of the URL
      // api_key, api_secret, user, token
      // Make sure all the needed parameters were set
      if(!$this->all_get_parameters_set(['api_key', 'api_secret', 'user', 'token'])) {
        return false;
      }
      // Bind all the get parameters to variables
      $api_key = $_GET['api_key'];
      $api_secret = $_GET['api_secret'];
      $user = $_GET['user'];
      $token = $_GET['token'];
      // Pass the variables into the normal api request verifier
      return $this->verify_api_request($api_key, $api_secret, $user, $token);
    }
    // Makes sure an account is active
    public function verify_account_active($username) {
      // Run a query to get the active property
      $sql = $this->conn->prepare("SELECT active FROM " . $this->database_connection['db_users_table'] . " WHERE user LIKE :username");
      $sql->bindParam(":username", $username);
      $success = $sql->execute();
      // Make sure it worked
      if(!$success) {
        return false;
      }
      // Get the property
      while ( $row = $sql->fetch() ) {
        return ($row['active'] == 1);
      }
      return false;
    }
    // MARK: - Input validation functions
    // Username uniqueness test
    private function verify_username_uniqueness($username) {
      // If the username does not exist yet, we're golden
      return !$this->verify_username_exists($username);
    }
    // Username existence test
    private function verify_username_exists($username) {
      // Prepare to select that user from the database (if they exist)
      $sql = $this->conn->prepare("SELECT id FROM " . $this->database_connection['db_users_table'] . " WHERE user LIKE :username");
      $sql->bindParam(":username", $username);
      // Execute the query
      $success = $sql->execute();
      // Make sure the query worked
      if(!$success) {
        return false;
      }
      // Count the number of results returned
      $results = 0;
      while( $row = $sql->fetch() ) {
        $results++;
      }
      // Make sure no results were returned, else the username is not unique
      return ($results == 1);
    }
    // Username string validity test
    private function verify_username_string_validity($username) {
      // Make sure the username is long enough
      if(strlen($username) < $this->configuration['username_requirements']['length']) {
        return false;
      }
      // Make sure the username is only alphanumeric
      for($i = 0; $i < strlen($username); $i++) {
        $char = substr( $username, $i, 1 );
        if(!ctype_alnum($char)) {
          return false;
        }
      }
      // If all the tests above worked, return true
      return true;
    }
    // Username validity test
    private function verify_username_validity($username) {
      $stringValid = $this->verify_username_string_validity($username);
      $unique = $this->verify_username_uniqueness($username);
      if(!$stringValid) {
        $this->log_error("Username \"" . $username . "\" is invalid");
      }
      if(!$unique) {
        $this->log_error("Username \"" . $username . "\" is not unique");
      }
      return ( $stringValid && $unique );
    }
    // Password validity test (length, uppercase, numbers, etc)
    private function verify_password_strength($password) {
      // Make sure the length is long enough
      if(strlen($password) < $this->configuration['password_strength']['length']) {
        $this->log_error("Password is too short");
        return false;
      }
      // Count the number of each type of character
      $lowercase = 0;
      $uppercase = 0;
      $numerals = 0;
      $specials = 0;
      $invalid = 0;
      // Loop over each character, adding to each bucket above
      for($i = 0; $i < strlen($password); $i++) {
        // Get the character at this position
        $char = substr( $password, $i, 1 );
        // Is it lowercase?
        if(ctype_lower($char)) {
          $lowercase++;
        } else if(ctype_upper($char)) {
          $uppercase++;
        } else if(ctype_digit($char)) {
          $numerals++;
        } else if($this->is_special_character($char)) {
          $specials++;
        } else {
          $invalid++;
        }
      }
      // Make sure no invalid characters were in the password
      if($invalid > 0) {
        $this->log_error("Password contains invalid characters");
        return false;
      }
      // Make sure enough lowercase were there
      if($this->configuration['password_strength']['lowercase'] > $lowercase) {
        $this->log_error("Didn't have at least " . $this->configuration['password_strength']['lowercase'] . " lowercase");
        return false;
      } else if($this->configuration['password_strength']['uppercase'] > $uppercase) {
        $this->log_error("Didn't have at least " . $this->configuration['password_strength']['uppercase'] . " uppercase");
        return false;
      } else if($this->configuration['password_strength']['numerals'] > $numerals) {
        $this->log_error("Didn't have at least " . $this->configuration['password_strength']['numerals'] . " numerals");
        return false;
      } else if($this->configuration['password_strength']['specials'] > $specials) {
        $this->log_error("Didn't have at least " . $this->configuration['password_strength']['specials'] . " special characters");
        return false;
      }
      // If everything else worked out, the password is valid
      return true;
    }
    // MARK: - Utility methods
    // Prepares the database table
    public function prepare_database() {
     
      // Create the users table
      $sql = $this->conn->prepare("CREATE TABLE IF NOT EXISTS `" . $this->database_connection['db_users_table'] . "` ( `id` int(11) NOT NULL AUTO_INCREMENT, `user` text NOT NULL, `password` text NOT NULL, `data` text NOT NULL, `active` int(11) NOT NULL DEFAULT '1', `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;");
      if(!$sql->execute()) {
        return false;
      }
      
      // Create the user's tokens table
      $sql = $this->conn->prepare("CREATE TABLE IF NOT EXISTS `" . $this->database_connection['db_tokens_table'] . "` (`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Irrelevant - Only exists to make sure each column is unique', `user` text NOT NULL, `token` text NOT NULL, `expiration` bigint(11) NOT NULL, `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=88 ;");
      if(!$sql->execute()) {
        return false;
      }
      
      // Create the API credentials table
      $sql = $this->conn->prepare("CREATE TABLE IF NOT EXISTS `" . $this->database_connection['db_api_table'] . "` (`id` int(11) NOT NULL AUTO_INCREMENT, `api_key` text NOT NULL, `api_secret` text NOT NULL, `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;");
      if(!$sql->execute()) {
        return false;
      }
      
      // Create the permissions table (this is the table with metadata about each permission)
      $sql = $this->conn->prepare("CREATE TABLE IF NOT EXISTS `" . $this->database_connection['db_permissions_table'] . "` (`permissionid` int(11) NOT NULL AUTO_INCREMENT,`permissionname` text NOT NULL,`permissiondescription` text NOT NULL,`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,PRIMARY KEY (`permissionid`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;");
      if(!$sql->execute()) {
        return false;
      }
      
      // Create the permissions assignments table (This lists all the permissions tied to each user)
      $sql = $this->conn->prepare("CREATE TABLE IF NOT EXISTS `" . $this->database_connection['db_permission_assignments_table'] . "` (`permissionassignmentid` int(11) NOT NULL AUTO_INCREMENT,`permissionid` int(11) NOT NULL,`userid` int(11) NOT NULL,`enabled` int(11) NOT NULL DEFAULT '1',`granted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY (`permissionassignmentid`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;");
      if(!$sql->execute()) {
        return false;
      }
      
      return true;
    }
    // Checks if a SQL object's recent query had only one result
    private function exactly_one_result_returned($sql) {
      $results = 0;
      while($row = $sql->fetch()) {
        $results++;
      }
      return ($results == 1);
    }
    // Verifies that all the keys in an array are keys in the $_GET[] array
    private function all_get_parameters_set($params) {
      for($i = 0; $i < count($params); $i++) {
        if(!isset($_GET[$params[$i]])) {
          return false;
        }
      }
      return true;
    }
    // Determines if a character is one of the allowed special characters
    // ( most of the ones on a standard English keyboard)
    private function is_special_character($c) {
      $specials = "~`!@#$%^&*()_-+={[]}|:;',./<>?";
      return (strpos($specials, $c) !== FALSE);
    }
    // Writes an error to the errors array
    private function log_error($e) {
      $this->errors[] = $e;
    }
    // Accessor for the errors property of this class
    public function get_errors() {
      return $this->errors;
    }
  }
?>
