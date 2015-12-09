<!-- BEGIN: HEADER -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title>{HEADER_TITLE} : {KEYWORDS}</title>
    <meta name="robots" content="index,follow" />
    <meta name="keywords" content="{KEYWORDS}" />
    <meta name="description" content="{DESCRIPTION}" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
    <link href="{SITE_URL}templates/styles.css" type="text/css" rel="stylesheet" />
    <link href="{SITE_URL}templates/ja-sosdmenu.css" type="text/css" rel="stylesheet" />
    {HEADER_JAVASCRIPTS}
</head>

<body>

<table cellpadding="0" cellspacing="0" style="width: 100%; height: 100%;">
<tr>
    <td style="padding-bottom: 6px;">
        <table cellpadding="0" cellspacing="0" style="width: 964px; margin-left: auto; margin-right: auto;">
            <tr>
                <td style="width: 200px;"><img src="{SITE_URL}images/logo.png" /></td>
                <td style="width: 764px; padding-left: 46px; padding-top: 38px;">
                    <div id="ja-mainnavwrap">
                        <div id="ja-mainnav" class="clearfix">
                            <ul class="menu">{TOP_MENU}</ul>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td style="vertical-align: top;">
        <table cellpadding="0" cellspacing="0" style="width: 964px; margin-left: auto; margin-right: auto;">
            <tr>
                <td style="background-color: #3ba2d2; text-align: center; padding-top: 7px; padding-bottom: 7px;">
                    <img src="{SITE_URL}images/view.jpg" align="center" />
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr style="height:20px;"><td></td></tr>
<tr>
    <td>
        <table cellpadding="0" cellspacing="0" style="width: 964px; margin-left: auto; margin-right: auto;">
        <tr>
            <td style="width: 240px; vertical-align: top;">
                <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="text-align:left; padding-left:0px; padding-right: 30px; padding-bottom: 10px;">
                        <!-- BEGIN: NEWS_ROW -->
                        <span class='newsSpan'>{ROW_DATE}</span> <br />
                        <a href='{SITE_URL}news/{ROW_ID}' class='newsLink'>{ROW_TITLE}</a> <br />
                        <p class="description" style='margin-top: 10px;'>{ROW_ARTICLE}</p>
                        <img src="{SITE_URL}images/separator.gif" style="margin-bottom:5px;margin-top:5px;" />
                        <!-- END: NEWS_ROW -->
                    </td>
                </tr>
                </table>
            </td>
            <td style="width: 724px; vertical-align: top;">


<!-- END: HEADER -->