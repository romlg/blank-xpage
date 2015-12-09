<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<form action='{ACTION_SCRIPT}' method='post' enctype='multipart/form-data'>
<table width='100%' cellspacing='0' cellpadding='2' class='box'>
    <tr><td height='10' colspan='3'></td></tr>
    <tr>
        <td width='300'><span class='name1'>Название сайта:</span></td>
        <td width='16'><img src='./images/icon_quest.gif' border='0' alt="Полное название сайта" title="Полное название сайта" /></td>
        <td>{MAIN_TITLE}</td>
    </tr>
    <tr><td></td><td></td><td><span class='error'>{MAIN_TITLE_ERROR}</span></td></tr>
    
    <tr>
        <td width='300'><span class='name1'>Логин Администратора:</span></td>
        <td width='16'><img src='./images/icon_quest.gif' border='0' alt="Логин администратора, необходим для входа в систему" title="Логин администратора, необходим для входа в систему" /></td>
        <td>{MAIN_ADMIN_USERNAME}</td>
    </tr>
    <tr><td></td><td></td><td><span class='error'>{MAIN_ADMIN_USERNAME_ERROR}</span></td></tr>
    <tr>
        <td>&nbsp;&nbsp;<span class='name1'>Новый пароль администратора:</span></td>
        <td>
            <img src='./images/icon_quest.gif' border='0' alt="Новый пароль администратора. Оставьте это поле пустым, если не хотите его менять" title="Новый пароль администратора. Оставьте это поле пустым, если не хотите его менять" />
        </td>
        <td> {MAIN_ADMIN_PASSWORD}</td>
    </tr>
    <tr><td></td><td></td><td><span class='error'>{MAIN_ADMIN_PASSWORD_ERROR}</span></td></tr>
    <tr>
        <td>&nbsp;&nbsp;<span class='name1'>Подтвердите новый пароль администратора:</span></td>
        <td>
            <img src='./images/icon_quest.gif' border='0' alt="Подтверждение нового пароля администратора. Оставьте это поле пустым, если не хотите его менять" title="Подтверждение нового пароля администратора. Оставьте это поле пустым, если не хотите его менять" />
        </td>
        <td>{MAIN_ADMIN_PASSWORD1}</td>
    </tr>
    <tr><td></td><td></td><td><span class='error'>{MAIN_ADMIN_PASSWORD1_ERROR}</span></td></tr>
    <tr>
        <td><span class='name1'>Контактный Email:</span></td>
        <td>
            <img src='./images/icon_quest.gif'  border='0' alt="На этот Email будет пересылаться информация со страницы контактов" title="На этот Email будет пересылаться информация со страницы контактов" />
        </td>
        <td> {MAIN_CONTACTEMAIL}</td>
    </tr>
    <tr><td></td><td></td><td><span class='error'>{MAIN_CONTACTEMAIL_ERROR}</span></td></tr>
    <tr><td colspan='3'><hr></td></tr>
    <tr>
        <td><span class='name1'>Текущий пароль администратора:</span></td>
        <td>
            <img src='./images/icon_quest.gif' border='0' alt="Текущий пароль администратора. Требуется для подтверждения изменений на этой странице и для входа в систему" title="Текущий пароль администратора. Требуется для подтверждения изменений на этой странице и для входа в систему" />
        </td>
        <td> {MAIN_CURRENT_PASSWORD}</td>
    </tr>
    <tr><td></td><td></td><td><span class='error'>{MAIN_CURRENT_PASSWORD_ERROR}</span></td></tr>
    <tr><td colspan='3'><hr></td></tr>
    <tr>
        <td><span class='message'>{MAIN_MESSAGE}</span></td><td></td><td colspan='3' align='left'> <input type='submit' value=" Обновить "> </td>
    </tr>
    <tr><td style='height:5px;' colspan='3'></td></tr>
</table>
<input type='hidden' name='ocd' value='update'>
</form>

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->