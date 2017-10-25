<?php

  // S5 stands for Super Simple Server Side Security 
  // It implements numerous basic api and user authentication for you
  // You can view the documentation at github.com/brendanmanning/S5/
  require 'S5.php';

  function has_permission($params, $connection) {
    
    // Determine whether or not this request should be authorized.
    
    // We suggest doing the following:
    // 1. Checking that a request-supplied token is valid
    //   Ex. todo/123?token=123abc456def (check $params["token"])
    // 2. For added control, you could also check for an API key and token
    //   Ex. todo/123?api_key=98zy&api_secret=mne678&token=123abc456def
    
    // We return true by default, but you should really change that
    return true;
  }
?>