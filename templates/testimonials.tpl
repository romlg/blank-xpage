
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
				<div style="padding:5px;text-align:center;">{MAIN_MESS}</div>
				 <form action='testimonials.php' method='POST' name='guest' onSubmit="return validateForm(guest);" style="margin-bottom:10px;">
                    <table border='0' cellpadding="0" cellspacing="0" width='100%'>
                        <tr>
                            <td valign="top" class="wPadding" align="right">
                               <span class="question">Имя* :</span>
                            </td>
                            <td valign="top" class="wPadding">
                                {MAIN_NAME}
                            </td>
                        </tr>
                        <tr><td></td><td class="wPadding"><span class="error">{MAIN_NAME_ERROR}</span><td></tr>
                        
                        <tr>
                            <td valign="top" class="wPadding" align="right">
                                <span class="question">Город :</span>
                            </td>
                            <td valign="top" class="wPadding">
                                {MAIN_CITY}
                            </td>
                        </tr>
                        
                        <tr>
                            <td valign="top" class="wPadding" align="right">
                                <span class="question">Email* :</span>
                            </td>
                            <td valign="top" class="wPadding">
                                {MAIN_EMAIL}
                            </td>
                        </tr>
                        <tr><td></td><td class="wPadding"><span class="error">{MAIN_EMAIL_ERROR}</span><td></tr>
                        <tr>
                            <td valign="top" class="wPadding" align="right">
                                <span class="question">Отзыв* :</span>
                            </td>
                            <td valign="top" class="wPadding">
                                {MAIN_DESCRIPTION}
                            </td>
                        </tr>
                        <tr><td></td><td class="wPadding"><span class="error">{MAIN_DESCRIPTION_ERROR}</span><td></tr>
                        <tr>
                            <td valign="top" class="wPadding" align="right"><span class="question">Проверочный код* :</span></td>
                            <td valign="top" class="wPadding"> {LOGIN_TURING} {LOGIN_TURING_IMAGE}</td>
                        </tr>
                        <tr><td></td><td class="wPadding"><span class="error">{LOGIN_TURING_ERROR}</span><td></tr>
                        <tr>
                            <td valign="top" class="wPadding">
                            </td>
                            <td valign="top" class="wPadding">
                                <input type='hidden' name='ocd' value='send'>
                                <input type='submit' value=" Добавить ">
                            </td>
                        </tr>
                        <tr style='height:3px;'><td colspan="2" style="border-top: 1px solid #6a9cd9;" class="wPadding"></td></tr>
                    </table>
                    </form>
                    
				
				
						
						
						
						<!-- BEGIN: TABLE_ROW -->
                    <table border='0' cellpadding="0" cellspacing="0" width='100%'>
                        <tr>
                            <td valign="top" style="border-bottom: 1px solid #6a9cd9;">
                                <span class='question'>{ROW_NAME}</span> <span class='question' style="font-weight:normal;">{ROW_CITY}</span>
                            </td>
                            <td valign="top" style="border-bottom: 1px solid #6a9cd9;" align="right">
                                <span class='question' style="font-weight:normal;">{ROW_DATE}</span>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" colspan="2">
                                <p style="font-size:10px;">{ROW_MESSAGE}</p>
                            </td>
                        </tr>
                        <tr style='height:5px;'>
                            <td colspan="2" style="border-bottom: 1px solid #6a9cd9;">
                            </td>
                        </tr>
                    </table><br />

                                    <!-- END: TABLE_ROW -->

                                    <!-- BEGIN: TABLE_EMPTY -->


                    <table border='0' cellpadding="0" cellspacing="0" width='100%'>
                        <tr>
                            <td align="center">
                                Список сообщений пуст...
                            </td>
                        </tr>
                    </table>

                                    <!-- END: TABLE_EMPTY -->
                                    
                                    <br />{MAIN_PAGES}
                                    
                                    
                                   
                                    
				</td>
		</tr>
		
</table>


           
                    
                    
{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->