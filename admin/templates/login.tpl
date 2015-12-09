<!-- BEGIN: MAIN -->
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang=ru>
<head>
    <title>{HEADER_TITLE}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
    <meta http-equiv="content-language" content="ru">
    <link href="./templates/styles.css" type="text/css" rel="stylesheet" />
</head>

<body>

<table height='100%' width="100%" cellpadding="0" cellspacing="0" align='center' border='0'>
    <tr>
        <td align='center' valign='middle'>
            
            <table width='400' border='0' cellspacing='0' cellpadding='0'>
                <tr style="height:34px;">
                    <td style="width:6px;background-image:url(./images/left_corner.gif);">
                    </td>
                    <td style="background-image:url(./images/center_top.gif);padding-bottom:7px;">
                        <span class="ptitle">Вход Администратора</span> <span class='error'>{LOGIN_ERROR}</span>
                    </td>
                    <td style="width:6px;background-image:url(./images/right_corner.gif);">
                    </td>
                </tr>
                <tr>
                    <td style="width:6px;background-image:url(./images/left.gif);">
                    </td>
                    <td>
                        <form name='login' method='POST' action='{MAIN_ACTION}'>

                        <table width="100%" cellpadding="2" cellspacing="0" border='0' align='center'>

                            <tr>
                                <td width='40%' align='right'>
                                    <span class='signs_b'>Логин:</span>
                                </td>
                                <td>
                                    {LOGIN_USERNAME}
                                </td>
                            </tr>
                            <tr>
                                <td align='right'>
                                    <span class='signs_b'>Пароль:</span>
                                </td>
                                <td>
                                    {LOGIN_PASSWORD} <input type='submit' value='Войти'>
                                </td>
                            </tr>
                        </table>

                        <input type='hidden' name='ocd' value='login'>
                        </form>
                 
                    </td>
                    <td style="width:6px;background-image:url(./images/right.gif);">
                    </td>
                </tr>
                <tr style="height:5px;">
                    <td style="width:6px;background-image:url(./images/bottom_left.gif);">
                    </td>
                    <td style="background-image:url(./images/bottom.gif);">
                    </td>
                    <td style="width:6px;background-image:url(./images/bottom_right.gif);">
                    </td>
                </tr>
            </table>
        
        </td>
    </tr>
    <tr style='height:80px;'><td></td></tr>
</table>

</body>
</html>

<!-- END: MAIN -->