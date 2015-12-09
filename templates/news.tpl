
<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
		<td class="centerHead"><h1>{MAIN_HEADER}</h1></td>
	</tr>
	<tr>
		<td class="centerContent">
						
			<table border='0' cellpadding="0" cellspacing="0" width='100%'>
                <tr>
                    <td valign='top'>{MAIN_PHOTO}</td>
                    <td valign='top' width='100%'>
                        <table border='0' cellpadding="2" cellspacing="0" width='100%'>
                            <tr style='height:22px;'>
                                <td style="padding-top:4px;">
                                    <span class='newsSpan' style="font-weight:bold;">{MAIN_DATE} : {MAIN_TITLE}</span>
                                </td>
                            </tr>
                            <tr>
                                <td valign='top'>
                                    <p class='description' style='margin-top:5px;'>
                                        {MAIN_ARTICLE}</i>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign='top'>{MAIN_PHOTO2}</td>
                </tr>
                <tr><td colspan='3' style='height:5px;'></td></tr>
                <tr>
                    <td colspan='3' valign='top'>
                        {MAIN_DESCRIPTION}
                    </td>
                </tr>
            </table>
						
		</td>
	</tr>
</table>

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->