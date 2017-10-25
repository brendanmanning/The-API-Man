<?php
  function has_all_parameters($parameters) {
    
    // parameters is an associative array containing all the request-supplied GET/POST values and their keys
    // For example, the request todos?count=100&startDate=January012017
    // Would yield ["count" => 100, "startDate" => "January012017"]
    
    // Perform some code here to make sure this request has all the required parameters
    
    // By default, we return true, but make sure to change this
    return in_array("name", $parameters);
    
  }
?>