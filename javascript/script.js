function disableButton(name,form)
{
    document.getElementById(name).style.display = "none";
    document.getElementById(form).submit();
}
function enableboth()
{
    document.getElementById("submiter").display = "inline";
    document.getElementById("reentermail").display = "none";
    
}

function q_server()
{
   
    var http = new XMLHttpRequest();
    var url = 'runner.php';
    var params = 'run_script=start';
    http.open('POST', url, true);

    //Send the proper header information along with the request
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            
        }
    }
    http.send(params);
}

function start()
{
    setInterval(q_server,10000);
}