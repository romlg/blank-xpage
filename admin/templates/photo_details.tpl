<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<form action='{MAIN_ACTION}' method='POST'  enctype='multipart/form-data'>

<table width='100%' border='0' cellspacing='0' cellpadding='2'>
    <tr>
        <td style="vertical-align: top;"><span class='name1'> Галерея :</span></td>
        <td style="vertical-align: top;">{MAIN_GALLERY}</td>
    </tr>
    <tr>
        <td style="vertical-align: top;"><span class='name1'>Описание :</span></td>
        <td style="vertical-align: top;">{MAIN_CONTENT}&nbsp; <span class='error'>{MAIN_CONTENT_ERROR}</span></td>
    </tr>
    <tr>
        <td width='20%'> <span class='name1'>Дата :</span> </td>
        <td width='80%'> {MAIN_DATE} </td>
    </tr>
    <tr>
        <td style="vertical-align: top;"> <span class='name1'>* Фото :</span> </td>
        <td> {MAIN_PHOTO} </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <input type='submit' value=" Принять " class='button' /> &nbsp;
            <input type='button' value=" Отмена " class='button' onClick="window.location.href='photos.php'" />
        </td>
    </tr>
    <tr>
        <td colspan='2'>
            <hr />
            <span class="name2">* - Только JPEG формат, рекомендуемый размер - не более 1.5 Мб. Все фотографии будут автоматически сжаты.</span>
        </td>
    </tr>
</table>
<input type='hidden' name='ocd' value='{MAIN_OCD}' />
<input type='hidden' name='id' value='{MAIN_ID}' />
</form>

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->