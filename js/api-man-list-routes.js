function getRoutes() {
  $.get(ROOT_URL + "internal-api/getroutes.php?username=" + USER_NAME + "&token=" + ACCESS_TOKEN, function(data, success) {
    if(success == "success") {
      var routes = JSON.parse(data);
      for(var route = 0; route < routes.length; route++) {
        $('#api-man-routes').append('<li class="collection-header" id="route-' + routes[route] + '"><div><a href="api-man-edit-route.php?route=' + routes[route] + '">' + routes[route] + '</a><a onclick="deleteRoute(\'' + routes[route] + '\')" class="secondary-content"><i class="material-icons">delete</i></a></div></li>');
      }
    }
  });
}

function deleteRoute(route) {

  if(!confirm("Are you sure you want to delete " + route + "\nAll of your work will be unrecoverable")) {
    return;
  }

  $.post("internal-api/deleteroute.php", {
        route: route,
        username: USER_NAME,
        token: ACCESS_TOKEN
    },
    function(data, status) {
    var json = JSON.parse(data);
      if(json['status'] == 200) {
      Materialize.toast(json['message'], 3000);
      var lielement = document.getElementById("route-" + route);
      lielement.parentNode.removeChild(lielement);
    } else if(json['status'] == 500) {
      Materialize.toast(json['message'], 5000);
    } else {
      Materialize.toast("An unknown error occured. Please try again later", 5000);
    }
    });
   
}
getRoutes();