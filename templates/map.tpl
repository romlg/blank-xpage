
<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr style="height:50px;">
				<td class="centerHead">
						<H1>{MAIN_HEADER}</H1>
				</td>
		</tr>
		<tr>
				<td class="centerContent">
						<table cellpadding="0" cellspacing="0" border='0' align='left'>
                <tr>
                    <td style='vertical-align:bottom;padding:2px;'>
                        <img src='./images/folder.gif' valign='bottom' alt='' />  <a href='{URL}' class='newsLink'>Главная</a>
                    </td>
                </tr>
                {MAIN_PAGES}
                <tr>
                    <td style='vertical-align:bottom;padding:2px;'>
                        <img src='./images/folder.gif' valign='bottom' alt='' />  <span class='question'>Новости</span>
                    </td>
                </tr>
                {MAIN_NEWS}
                <tr>
                    <td style='vertical-align:bottom;padding:2px;'>
                        <img src='./images/folder.gif' valign='bottom' alt='' />  <a href='{URL}countries' class='newsLink'>Страны</a>
                    </td>
                </tr>
                {MAIN_COUNTRIES}
                <tr>
                    <td style='vertical-align:bottom;padding:2px;'>
                        <img src='./images/folder.gif' valign='bottom' alt='' />  <a href='{URL}search' class='newsLink'>Поиск тура</a>
                    </td>
                </tr>
                <tr>
                    <td style='vertical-align:bottom;padding:2px;'>
                        <img src='./images/folder.gif' valign='bottom' alt='' />  <a href='{URL}testimonials' class='newsLink'>Отзывы</a>
                    </td>
                </tr>
                                
            </table>
				</td>
		</tr>
</table>

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->