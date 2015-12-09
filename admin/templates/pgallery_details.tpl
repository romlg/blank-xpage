<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<form action='{MAIN_ACTION}' method='POST'  enctype='multipart/form-data'>
<table width='100%' border='0' cellspacing='0' cellpadding='2'>
    <tr><td colspan='2' style='height:10px;'></td></tr>
    <tr>
        <td><span class='name1'>Название:</span></td>
        <td>{MAIN_TITLE}</td>
    </tr>
    <tr><td></td><td><span class='error'>{MAIN_TITLE_ERROR}</span></td></tr>
    <tr>
        <td valign='top'><span class='name1'>Краткое описание:</span></td>
        <td>{MAIN_DESCRIPTION}</td>
    </tr>
    <tr>
        <td valign='top'><span class='name1'>Полное описание:</span></td>
        <td>{MAIN_PDESCRIPTION}
        <script language='JavaScript'>
                var oEdit1 = new InnovaEditor ("oEdit1");
    
                oEdit1.width=600;
                oEdit1.height=360;
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
                oEdit1.cmdAssetManager = "modalDialogShow('../../assetmanager/assetmanager.php',640,465)";

                /***************************************************
                  SETTING EDITING MODE
                  Possible values:
                    - "HTMLBody" (default) 
                    - "XHTMLBody" 
                    - "HTML" 
                    - "XHTML"
                ***************************************************/
                oEdit1.mode="HTMLBody";
                oEdit1.REPLACE ("pdescription");
            </script>
        </td>
    </tr>
    <tr>
        <td valign='top'><span class='name1'>Дата регистрации:</span></td>
        <td>{MAIN_DATE}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
            <input type='submit' value=" Принять "> &nbsp;
            <input type='button' value=" Отмена " onClick="window.location.href='{MAIN_CANCEL_URL}'">
        </td>
    </tr>
    <tr><td colspan='2' style='height:10px;'></td></tr>
</table>
<input type='hidden' name='content' value='{MAIN_CONTENT}'>
<input type='hidden' name='ocd' value='{MAIN_OCD}'>
<input type='hidden' name='id' value='{MAIN_ID}'>
</form>

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->