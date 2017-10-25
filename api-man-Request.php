<?php

  require_once 'api-man-config.php';
  require_once 'api-man-Route.php';
  require_once 'api-man-utilities.php';

  class Request {
   
   /**
    * The request type
    */
   private $type = null; // Usually "POST" or "GET"
   
   /**
    * The request parameters
    */
   public $parameters = [];
    
   /**
    * The request route (an instance of the Request object)
    */
   private $route = null; 
   
   /**
    * Are all the required parameters filled?
    */
   private $parameters_ok = false;
   
   /**
    * Was the request properly autorized?
    */
   private $autorization_ok = false;
   
   /**
    * The database connection we will pass to the request handler
    */
   private $database_connection = null;
  
   /**
    * Constructor for objects of the Request type
    * @param $route_folder The absolute path to the folder containing the request handler, permissions handler, and parameters handler, etc.
    */
   function __construct($route_folder) {
     
     $this->type = $_SERVER['REQUEST_METHOD'];
     
     $this->get_parameters();
     $this->route = new Route($route_folder);
     
     $route_handler_location = make_relative($this->route->route_handler_path());
     $permissions_handler_location = make_relative($this->route->permissions_handler_path());
     $parameters_handler_location = make_relative($this->route->parameters_handler_path());
     
     include("$route_handler_location");
     include("$permissions_handler_location");
     include("$parameters_handler_location");
     
     $this->database_connection = database_connection();
     
     $this->parameters_ok = has_all_parameters($this->parameters);
     $this->autorization_ok = has_permission($this->parameters, $this->database_connection);
     
   }
   
   /**
    * Execute the API request.
    * If the parameters or autorization were not okay, we will output an error message here
    */
   public function execute() {
   
     $output = array();
   
     if(!$this->parameters_ok) {
       $output = ["status" => 422, "error" => "You were missing a required parameter"];
     } else if(!$this->autorization_ok) {
       $output = ["status" => 403, "error" => "You did not provide proper autorization for this request"];
     } else {
       $output = request($this->parameters, $this->database_connection); 
     }
     
     echo json_encode($output);
   }
   
   /**
    * Utility method - Fill the parameters array based on the REQUEST_METHOD
    */
   private function get_parameters() {
     if($this->type == "POST") {
       foreach ($_POST as $param_name => $param_val) {
         $this->parameters[$param_name] = $param_val;
       }
     } else if($this->type == "GET") {
       foreach ($_GET as $param_name => $param_val) {
         $this->parameters[$param_name] = $param_val;
       }
     }
   }
  }