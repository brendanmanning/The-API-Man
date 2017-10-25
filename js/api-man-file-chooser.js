// Define the template for a file list item
var modalListItemHtml = '<li class="collection-item avatar"><i class="material-icons circle">{type}</i><a class="title" onclick="pathSelected({index})">{name}</a><a href="#!" class="secondary-content"><i class="material-icons">delete</i></a></li>';

/**
 * An enum of the two types of input we can prompt for: file or folder
 */
var PathChooserPromptType = {
  File: 1,
  Folder: 2
};
 
/**
 * The callback that is called when a file or folder is selected
 */
 var pathSelectedCallback = function(selected){ console.log(selected); };
 
/**
 * The file selection mode that we will be using
 */
var pathSelectionMode = PathChooserPromptType.File;

 var currentdirectory = "";

 var folders = [];
 var files = [];
 
 var levels = 0;
 
/**
 * Load a folder
 * @param The path to the folder we want to show. You may omit the path on the server to your API Man installation root
 */
 var html = "";
 
function load(directory) {

console.log("loading " + directory);
 
  // Apply aliases for the root directory
  if(directory == undefined) {
    directory = '/';
  }
  
  currentdirectory = directory;
 
  $.get(ROOT_URL + "internal-api/filebrowser.php?path=" + directory + "&username=" + USER_NAME + "&token=" + ACCESS_TOKEN, function(data, success) {

     // Make sure it worked
     if(data == null || data == undefined) {
       return;
     } 
     
     data = JSON.parse(data);
     folders = [];
     files = [];
  
     // Categorize everything as a file or a folder
     for(var i = 0; i < data.length; i++) {
       if(data[i]['type'] == 'directory') {
         folders.push(data[i]);
       } else if(data[i]['type'] == 'file') {
         files.push(data[i]);
       }
     }
     
     // Create a list of these files and folders
     html = '';
     for(var fol = 0; fol < folders.length; fol++) {
       html += modalListItemHtml.replace(/{type}/g, 'folder').replace(/{name}/g, folders[fol]['name']).replace(/{index}/g, fol).replace(/{path}/, directory);
     }
     for(var fi = 0; fi < files.length; fi++) {
       html += modalListItemHtml.replace(/{type}/g, 'description').replace(/{name}/g, files[fi]['name']).replace(/{index}/g, (fi + fol)).replace(/{path}/, directory);
     }
     
     document.getElementById("api-man-file-chooser-list").innerHTML = html;
     
  });
}

/**
 * Callback notified when the user selects a file/folder.
 * If selection mode is file:
 *   - If a folder is selected, go to it
 *   - If a file is selected, return it
 * If selection mode is folder:
 *   - TODO: Implement this 
 * @param selected The file or folder object they selected
 */
function pathSelected(index) {
  
  if(pathSelectionMode == PathChooserPromptType.File) {
  
    var selected = null;
  
    if(index < folders.length) {
      
      selected = folders[index];
      
    } else {
    
      selected = files[index - folders.length];
      
    }
    
    if(selected["type"] == "file") {
      
      pathSelectedCallback(selected);
      $('#api-man-file-chooser').modal('close');
      
    } else {
      
      levels++;
      load(selected["path"]);
      
    }
  
  }
}

/**
 * Go up a directory
 */
function navigateUp() {
  levels--;
  if(levels >= 0) {
    var newdirectory = currentdirectory.substring(0, currentdirectory.lastIndexOf("/"));
    load(newdirectory);
  }
}

/**
 * Register a callback for when a file or folder is selected
 * @param callback The callback which will recieve the folder or file object
 */
function registerPathSelectionCallback(callback) {
  pathSelectedCallback = callback;
}

 $(document).ready(function(){
    
     $('.modal').modal();
     load();
  });
  
  
  function promptForScript(callback) {
    registerPathSelectionCallback(callback);
    load("scripts");
    $('#api-man-file-chooser').modal("open");
  }
