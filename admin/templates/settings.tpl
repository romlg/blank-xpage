<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>
    <tr><td height='20'><H4>{MAIN_HEADER}</H4></td></tr>
    <tr><td height='1' bgcolor='#990000'></td></tr>
    <tr><td height='12'></td></tr>
    <tr><td align='center'><span class='message'>{MAIN_MESSAGE}</span></td></tr>
</table>

<form action='{ACTION_SCRIPT}' method='POST'>
<table width='100%' border='0' cellspacing='0' cellpadding='4' align='left' class='box'>
    <tr><td colspan='3'><H3>Общие настройки</H3></td></tr>

    <tr>
        <td>&nbsp;&nbsp;<span class='sign'>Заголовок сайта:</span></td>
        <td width='20'>
            <img src='./images/qwest.png' width='16' border='0' alt="Заголовок сайта">
        </td>
        <td> {MAIN_SITE_TITLE}</td>
    </tr>
    <tr><td></td><td></td><td><span class='error'>{MAIN_SITE_TITLE_ERROR}</span></td></tr>
    <tr>
        <td>&nbsp;&nbsp;<span class='sign'>URL сайта:</span></td>
        <td width='20'>
            <img src='./images/qwest.png' width='16' border='0' alt="URL корневого каталога сайта">
        </td>
        <td>{MAIN_SITE_URL}</td>
    </tr>
    <tr><td></td><td></td><td><span class='error'>{MAIN_SITE_URL_ERROR}</span></td></tr>
    <tr>
        <td>&nbsp;&nbsp;<span class='sign'>Физический путь к сайту:</span></td>
        <td width='20'>
            <img src='./images/qwest.png' width='16' border='0' alt="Физический путь к корневому каталогу сайта на сервере">
        </td>
        <td> {MAIN_PATH_SITE}</td>
    </tr>
    <tr><td></td><td></td><td><span class='error'>{MAIN_PATH_SITE_ERROR}</span></td></tr>

    <tr><td colspan='3'><hr></td></tr>

    <tr>
        <td colspan='3' align='center'><input type='submit' value=" Обновить "></td>
    </tr>
    <tr><td style='height:10px;' colspan='3'></td></tr>
</table>
<input type='hidden' name='ocd' value='update'>
</form>

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->