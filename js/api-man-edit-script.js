function listScripts() {
  $.get(ROOT_URL + "internal-api/getscripts.php?username=" + USER_NAME + "&token=" + ACCESS_TOKEN, function(data, status) {
    var json = JSON.parse(data);
    for(var s = 0; s < json.length; s++) {
      document.getElementById("script-list").innerHTML += '<li><a class="waves-effect" onclick="editScript(\'' + json[s] + '\')">' + json[s] + '</a></li>';
    }
  });
}

var editor = null;
var script = null;

function editScript(scriptname) {
script = scriptname;
$.get(ROOT_URL + "internal-api/getscript.php?script=" + scriptname + "&username=" + USER_NAME + "&token=" + ACCESS_TOKEN, function(data, status) {
  editor.setValue(data);
});
}

function saveScript() {

  if(script == null) {
    Materialize.toast("Please select a script by clicking the hamburger menu (in the bottom right) first", 5000);
    return;
  }

  $.post(ROOT_URL + "internal-api/savescript.php", {
    script: script,
    contents: editor.getValue(),
    username: USER_NAME,
    token: ACCESS_TOKEN
  }, function(data, status) {
    var json = JSON.parse(data);
    if(json['status'] == 201) {
      Materialize.toast(json['message'], 3000);
    } else if(json['status'] == 500) {
      Materialize.toast(json['message'], 5000);
    } else {
      Materialize.toast("An unknown error occured. Please try again later", 5000);
    }
  });
}

function createScript() {
  var scriptname = document.getElementById("NEW_SCRIPT_NAME").value;
  
  if(!scriptname.endsWith(".php")) {
    scriptname += ".php";
  }
  
  $.post(ROOT_URL + "internal-api/createscript.php", {
    script: scriptname,
    username: USER_NAME,
    token: ACCESS_TOKEN
  }, function(data, status) {
    var json = JSON.parse(data);
    if(json['status'] == 201) {
      Materialize.toast(json['message'], 3000);
      document.getElementById("script-list").innerHTML += '<li><a class="waves-effect" onclick="editScript(\'' + scriptname + '\')">' + scriptname + '</a></li>';
    } else if(json['status'] == 500) {
      Materialize.toast(json['message'], 5000);
    } else {
      Materialize.toast("An unknown error occured. Please try again later", 5000);
    }
  });
}

function prepareEditor() {
  editor = ace.edit("editor");
  
  var PHPMode = ace.require("ace/mode/php").Mode;
  
  editor.setTheme("ace/theme/sqlserver");
  editor.session.setMode(new PHPMode());
  
  editor.setValue("//Select a script using the hamburger menu to edit...");
}
$(document).ready(function(){
listScripts();
prepareEditor();
});