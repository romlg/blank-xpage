<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<form action='{MAIN_ACTION}' method='POST'  enctype='multipart/form-data'>
<table width='100%' border='0' cellspacing='0' cellpadding='2'>
    <tr>
        <td><span class='name1'>Дата:</span> </td>
        <td>{MAIN_DATE}</td>
    </tr>

    <tr>
        <td valign='top'><span class='name1'>Заголовок:</span> </td>
        <td> {MAIN_TITLE}</td>
    </tr>

    <tr>
        <td></td><td><span class='error'>{MAIN_TITLE_ERROR}</span></td>
    </tr>

    <tr>
        <td valign='top'><span class='name1'>Краткое описание:</span> </td>
        <td> {MAIN_ARTICLE}</td>
    </tr>

    <tr>
        <td></td><td><span class='error'>{MAIN_ARTICLE_ERROR}</span></td>
    </tr>

    <tr><td colspan='2' height='10'></td></tr>

    <tr>
        <td></td>
        <td>
            <table width='60%' cellspacing='0' cellpadding='3' align='left'>
                <tr>
                    <td  valign='top' width='50%'> <span class='name1'>* Фото 1:</span> </td>
                    <td  valign='top'> <span class='name1'>* Фото 2:</span> </td>
                </tr>
                <tr>
                    <td align='left'> {MAIN_PHOTO} </td>
                    <td align='left'> {MAIN_PHOTO2} </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <hr />
            <span class="name2">* - Только JPEG формат, рекомендуемый размер - не более 1.5 Мбт. Все фотографии будут автоматически сжаты.</span>
        </td>
    </tr>
    <tr><td colspan='2' style="height: 10px;"></td></tr>
    
    <tr>
        <td width='15%' valign='top'>
            <span class='name1'>Полное описание:</span>
        </td>
        <td width='75%' valign='top'>
            <textarea style="width: 680px; height: 340px;" id="progress" name="progress">{MAIN_DESCRIPTION}</textarea>
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
                oEdit1.REPLACE ("progress");
            </script>
        </td>
    </tr>
    <tr><td colspan='2'><hr></td></tr>
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