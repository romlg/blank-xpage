var xmlHttp
var str1 = 0;
function is_active (table, field, id)
{ 
    xmlHttp=GetXmlHttpObject()

    if (xmlHttp==null)
    {
        alert ("Browser does not support HTTP Request")
        return
    } 
    var url="is_active.php"
    url=url+"?table="+table
    url=url+"&field="+field
    url=url+"&id="+id
    url=url+"&sid="+Math.random()
    str1 = id


    xmlHttp.onreadystatechange=stateChanged
    xmlHttp.open("GET",url,true)
    xmlHttp.send(null)
}
function stateChanged()
{ 
    if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
    { 
        id = "resultik" + str1
        document.getElementById(id).innerHTML=xmlHttp.responseText
    }
}

function GetXmlHttpObject()
{ 
    var objXMLHttp=null


    if (window.XMLHttpRequest)
    {
        objXMLHttp=new XMLHttpRequest()
    }
    else if (window.ActiveXObject)
    {
        objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")
    }
    return objXMLHttp
}