
function clock ()
{
    var hours = theTime.getHours ();
    var minutes = theTime.getMinutes ();
    var seconds = theTime.getSeconds ();
    var m;
    theTime.setSeconds (seconds + 1);
    var day = theTime.getDate ();
    if (minutes <= 9) minutes = "0" + minutes;
    if (seconds <= 9) seconds = "0" + seconds;
    m = theTime.getMonth ();
    showTime = day+" "+month[m]+" "+theTime.getFullYear()+" "+hours+":"+minutes+":"+seconds;
    if (document.layers) {
        document.layers.disp1.document.write (showTime);
        document.layers.disp1.document.close ();
    }
    else if (document.all) {
        disp1.innerHTML = showTime;
    }
    else {
        document.getElementById('disp1').innerHTML = showTime;
    }
    setTimeout ("clock()", 1000);
} 

