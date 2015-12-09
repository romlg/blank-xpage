<!-- BEGIN: HEADER -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>{HEADER_TITLE}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="templates/styles.css" type="text/css" rel="stylesheet" />

    {HEADER_JAVASCRIPTS}
    <script language='JavaScript'>
    <!--
        var theTime = new Date ("{HEADER_SERVER_TIME}");
        var month = new Array ("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
    //-->
    </script>
<script language="JavaScript" type="text/javascript" src="../js/clock.js"></script>
</head>
<body onLoad="clock ();">

<table width='100%' border='0' cellspacing='0' cellpadding='0'>
    <tr style="height:50px;">
        <td colspan="2" valign='middle' style="background-image:url(./images/body.jpg);padding-right:20px;padding-left:20px;padding-bottom:10px;">
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                <tr>
                    <td width="50%">
                        <span class="top_header">{HEADER_SITE}</span>
                    </td>
                    <td width="50%" align="right">
                        <span id="disp1"></span>
                    </td>
                </tr>
            </table>
        </td>
    <tr>
    <tr>
        <td style="width:170px;padding:10px;vertical-align:top;">
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                <tr>
                    <td style="background-image:url(./images/menu_fon.gif);height:30px;text-align:right;">
                        {MENU_ADMINDETAILS} <img src="./images/menu_arrow.gif" alt="" valign="bottom" />    
                    </td>
                </tr>
                <tr>
                    <td style="background-image:url(./images/menu_fon.gif);height:30px;text-align:right;">
                        {MENU_PAGES} <img src="./images/menu_arrow.gif" alt="" valign="bottom" />    
                    </td>
                </tr>
                <tr>
                    <td style="background-image:url(./images/menu_fon.gif);height:30px;text-align:right;">
                        {MENU_NEWS} <img src="./images/menu_arrow.gif" alt="" valign="bottom" />
                    </td>
                </tr>
                <tr>
                    <td style="background-image:url(./images/menu_fon.gif);height:30px;text-align:right;">
                        {MENU_REPORTS} <img src="./images/menu_arrow.gif" alt="" valign="bottom" />
                    </td>
                </tr>
                <tr>
                    <td style="background-image:url(./images/menu_fon.gif);height:30px;text-align:right;">
                        {MENU_PHOTOS} <img src="./images/menu_arrow.gif" alt="" valign="bottom" />    
                    </td>
                </tr>
                <tr>
                    <td style="background-image:url(./images/menu_fon.gif);height:30px;text-align:right;">
                        {MENU_BACKUP} <img src="./images/menu_arrow.gif" alt="" valign="bottom" />    
                    </td>
                </tr>
                <tr>
                    <td style="background-image:url(./images/menu_fon.gif);height:30px;text-align:right;">
                        {MENU_LOGOUT} <img src="./images/menu_arrow.gif" alt="" valign="bottom" />    
                    </td>
                </tr>
            </table>        
        </td>
        <td style="padding-top:10px;padding-left:10px;vertical-align:top;">
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                <tr style="height:34px;">
                    <td style="width:6px;background-image:url(./images/left_corner.gif);">
                    </td>
                    <td style="background-image:url(./images/center_top.gif);padding-bottom:7px;">
                        <span class="ptitle">{HEADER_PAGE}</span>
                    </td>
                    <td style="width:6px;background-image:url(./images/right_corner.gif);">
                    </td>
                </tr>
                <tr>
                    <td style="width:6px;background-image:url(./images/left.gif);">
                    </td>
                    <td>

<!-- END: HEADER -->