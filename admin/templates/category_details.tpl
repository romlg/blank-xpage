<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}


<form action='{MAIN_ACTION}' method='POST'  enctype='multipart/form-data'>
<table width='100%' border='0' cellspacing='0' cellpadding='2'>
    <tr>
        <td width='15%' align='left' valign='top'> <span class="name1">Подкатегория :</span> </td>
        <td width='85%'> {MAIN_SELECT}</td>
    </tr>
    <tr>
        <td width='15%' align='left' valign='top'> <span class="name1">Название категории :</span> </td>
        <td width='85%'> {MAIN_TITLE}</td>
    </tr>
    <tr><td></td><td><span class='error'>{MAIN_TITLE_ERROR}</span></td></tr>
    
    <tr>
        <td align='left' valign='top' colspan='2'> <span class="name1">Описание :</span> </td>
    </tr>
    <tr>
        <td colspan='2'> {MAIN_CONTENT}
        <script language='JavaScript'>
                var oEdit1 = new InnovaEditor ("oEdit1");
    
                oEdit1.width=540;
                oEdit1.height=400;

                oEdit1.btnPrint=false;
                oEdit1.btnLTR=true;
                oEdit1.btnRTL=true;
                oEdit1.btnSpellCheck=false;
                oEdit1.btnStrikethrough=true;
                oEdit1.btnSuperscript=true;
                oEdit1.btnSubscript=true;
                oEdit1.btnClearAll=true;
                oEdit1.btnSave=false;
                oEdit1.btnStyles=true;

                /***************************************************
                  ENABLE ASSET MANAGER ADD-ON
                ***************************************************/
                oEdit1.cmdAssetManager = "modalDialogShow('../../assetmanager/assetmanager.php',540,400)";

                /***************************************************
                  SETTING EDITING MODE
                  Possible values:
                    - "HTMLBody" (default) 
                    - "XHTMLBody" 
                    - "HTML" 
                    - "XHTML"
                ***************************************************/
                oEdit1.mode="HTMLBody";

    
                oEdit1.REPLACE ("content");
            </script>
             </td>
    </tr>
    <tr><td colspan='2' height='10'></td></tr>
    
    <tr>
        <td>&nbsp;</td>
        <td>
            <input type='submit' value=" Принять "> &nbsp;
            <input type='button' value=" Отмена " onClick="window.location.href='{MAIN_CANCEL_URL}'">
        </td>
    </tr>
</table>
<input type='hidden' name='ocd' value='{MAIN_OCD}'>
<input type='hidden' name='id' value='{MAIN_ID}'>
</form>
<table width='100%' border='0' cellspacing='0' cellpadding='0'>
    <tr><td height='12'></td></tr>
</table>

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->