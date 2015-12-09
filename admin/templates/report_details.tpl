<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<form action='{MAIN_ACTION}' method='post' enctype='multipart/form-data'>
<table width='100%' border='0' cellspacing='0' cellpadding='2'>
    <tr><td colspan='2' style='height:10px;'></td></tr>
    <tr>
        <td><span class='name1'>Название:</span></td>
        <td>{MAIN_TITLE}</td>
    </tr>
    <tr><td></td><td><span class='error'>{MAIN_TITLE_ERROR}</span></td></tr>
    <tr>
        <td style="vertical-align: top;"><span class='name1'>Описание:</span></td>
        <td>{MAIN_DESCRIPTION}</td>
    </tr>
    <tr>
        <td><span class='name1'>Дата регистрации:</span></td>
        <td>{MAIN_DATE}</td>
    </tr>
    <tr>
        <td style="vertical-align: top;"><span class='name1'>Документ с отчетом:</span></td>
        <td>{MAIN_DOCUMENT}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
            <input type='submit' value=" Принять " /> &nbsp;
            <input type='button' value=" Отмена " onClick="window.location.href='{MAIN_CANCEL_URL}'" />
        </td>
    </tr>
    <tr><td colspan='2' style='height:10px;'></td></tr>
</table>
<input type='hidden' name='ocd' value='{MAIN_OCD}' />
<input type='hidden' name='id' value='{MAIN_ID}' />
</form>

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->