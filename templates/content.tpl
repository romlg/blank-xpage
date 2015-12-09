
<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="centerHead"><h1>{MAIN_HEADER}</h1></td>
	</tr>
	<tr>
		<td class="centerContent">
			{MAIN_CONTENT}
		</td>
	</tr>
</table>

<!-- BEGIN: FORM -->
<form name="quest" action="{MAIN_URL}2" method="post" enctype="multipart/form-data">
<table width="100%" cellpadding="2" cellspacing="0">
    <tr><td colspan="2" style="height: 10px;" class="wPadding"></td></tr>
    <tr>
        <td colspan="2" class="wPadding">
            <p class='description'>
                <i>Здесь Вы можете отправить нам сообщение</i>
            </p>
        </td>
    </tr>
    <tr><td colspan="2" class="wPadding"  style="text-align:center;"><span class='message'>{MAIN_MESS}</span></td></tr>
    <tr><td colspan="2" class="wPadding" style="height: 10px;"></td></tr>
    
    <tr>
        <td valign='top' align="right" class="wPadding">
            <span class='question'>Имя:</span>
        </td>
        <td class="wPadding">
            {MAIN_NAME}
        </td>
    </tr>
    <tr><td></td><td class="wPadding"><span class='error'>{MAIN_NAME_ERROR}</span></td></tr>
    <tr>
        <td valign='top' align="right" class="wPadding">
            <span class='question'>E-mail:</span>
        </td>
        <td class="wPadding">{MAIN_EMAIL}</td>
    </tr>
    <tr><td></td><td class="wPadding"><span class='error'>{MAIN_EMAIL_ERROR}</span></td></tr>
    <tr>
        <td valign='top' align="right" class="wPadding">
            <span class='question'>Сообщение:</span>
        </td>
        <td class="wPadding">{MAIN_MESSAGE}</td>
    </tr>
    <tr><td></td><td class="wPadding"><span class='error'>{MAIN_MESSAGE_ERROR}</span></td></tr>
    <tr>
        <td></td>
        <td class="wPadding">
            <input type="submit" value=" Отправить " />
        </td>
    </tr>
    <tr><td colspan="2" style="height: 12px;" class="wPadding"></td></tr>
</table>
<input type="hidden" name="ocd" value="send" />
</form>
<!-- END: FORM -->

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->