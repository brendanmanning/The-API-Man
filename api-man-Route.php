<?php
  class Route {
  
    private $directory = null;

    /**
     * Constructor for objects of the Route type 
     */
    function __construct($directory) {
      $this->directory = $directory;
      
      if(substr($this->directory, -1) != DIRECTORY_SEPARATOR) {
        $this->directory .= DIRECTORY_SEPARATOR;
      }
    }
    
    /**
     * Get the absolute file system path for this Route
     * @return An absolute file path as a string
     */
    public function route_path() {
      return $this->directory;
    }
    
    /**
     * Get the absolute URL of the handler file
     * @return The absolute file path of the handler script as a string
     */
    public function route_handler_path() {
      return $this->directory . "index.php";
    }
    
    /**
     * Get the text of the handler file
     * @return The text of the handler script which should be PHP code
     */
    function route_handler() {
      return file_get_contents($this->route_handler_path());
    }
    
    /**
     * Gets the absolute URL of the permissions file
     * @return The absolute file path of the permissions file as a string
     */
    public function permissions_handler_path() {
      return $this->directory . "permissions.php";
    } 
    
    /**
     * Get the text of the permissions file
     * @return The text of the permissions script which should be PHP code
     */
    function permissions_handler() {
      return file_get_contents($this->permissions_handler_path());
    }
    
    /**
     * Gets the absolute URL of the parameters file
     * @return The absolute file path of the parameters script as a string
     */
    public function parameters_handler_path() {
      return $this->directory . "parameters.php";
    } 
    
    /**
     * Get the text of the parameters file
     * @return The text of the parameters file which should be PHP code
     */
    function parameters_handler() {
       return file_get_contents($this->parameters_handler_path());
    }
     
  }
?>