
<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="centerHead"><h1>{MAIN_HEADER}</h1></td>
	</tr>
	<tr>
		<td class="centerContent">

			<table border='0' cellpadding="0" cellspacing="0" width='100%'>
            <tr style="vertical-align: top;">
                <!-- BEGIN: PHOTO_ROW -->

                    {ROW_UP}
                    <table border='0' cellpadding="0" cellspacing="0">
                        <tr>
                        	<td style="text-align:center; padding:2px;">
                        		<span class='question'>{ROW_TITLE}</span>
                        	</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                            	{ROW_PHOTO}
                            </td>
                        </tr>
                    </table>
                    {ROW_DOWN}

                <!-- END: PHOTO_ROW -->
            </tr>
            </table>
                    
		</td>
    </tr>
</table>

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->