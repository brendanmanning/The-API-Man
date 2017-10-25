/**
 * Download a url's contents and pass it to a callback
 * @param url The URL to download (as a String)
 * @param params (optional) POST params for this request
 * @param callback The callback to recieve the contents
 * This was found at https://stackoverflow.com/a/28728475/4988353
 */
function fetch(url,params,callback) {
    http=new XMLHttpRequest();
    if(params!=null) {
        http.open("POST", url, true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    } else {
        http.open("GET", url, true);
    }
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            callback(http.responseText);
        }
    }
    http.send(params);
}