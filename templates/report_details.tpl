
<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="centerHead"><h1>{MAIN_HEADER}</h1></td>
	</tr>
	<tr>
		<td class="centerContent">
			<p style="padding-bottom: 10px;">{MAIN_C_DESCR}</p>
						
			{MAIN_DOWNLOAD_REPORT}
			
			<div class="daGallery">
            <table border='0' cellpadding="0" cellspacing="0" width='100%'>
                <tr style="vertical-align: top;">
                <!-- BEGIN: PHOTO_ROW -->

                    {ROW_UP}
                    <table border='0' cellpadding="0" cellspacing="0" style="margin-left: auto; margin-right: auto;">
                        <tr>
                            <td style="text-align:center;">{ROW_PHOTO}</td>
                        </tr>
                        <tr>
                        	<td style="text-align:center; padding:2px;">
                        		<p class='description' style='margin:0px;'>{ROW_CONTENT}</p>
                        	</td>
                        </tr>
                    </table>
                    {ROW_DOWN}
                    
                <!-- END: PHOTO_ROW -->
                </tr>
            </table>
            </div>

	   </td>
	</tr>
	<tr>
        <td style="text-align: center;">{BACK}</td>
    </tr>
    <tr style="height:10px;"><td></td></tr>
</table>

<script type="text/javascript">
<!--//
  DaGallery.init();
//-->
</script>

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->