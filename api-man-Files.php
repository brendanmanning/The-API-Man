<?php
 


  /**
   * Get all the files and folders in a specific location
   * @return An array of objects containing the following properties: name (The file/folder's name), type: ('file','folder'), path: (The absolute path as a string)
   */
   function get_files( $dir = ROOT_DIR ) {
   
     $files = scandir($dir); // Get all the files and folders
     $contents = []; // Build an array of custom objects from directory
     
     foreach($files as $key => $value){
      
        // Exclude unix links to this directory and to one directory up
        if($value == "." || $value == "..") {
          continue;
        }
     
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        
        if(!is_dir($path)) {
          $contents[] = [
            'name' => $value,
            'type' => 'file',
            'path' => $path
          ];
        } else {
          $contents[] = [
            'name' => $value,
            'type' => 'directory',
            'path' => $path
          ];
        }
      }
      
      return $contents;
   }
?>