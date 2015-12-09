<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<table width='100%' border='0' cellspacing='0' cellpadding='0'>
    <tr><td align='right'> {MAIN_ADDLINK} &nbsp; </td></tr>
</table>

<table border='0' cellpadding="0" cellspacing="0" width='100%'>
    <tr valign='top'>
        <!-- BEGIN: TABLE_ROW -->
            {ROW_UP}
            <table border='0' cellpadding="2" cellspacing="0" style="margin-left: auto; margin-right: auto;">
                <tr>
                    <td><img src='./images/dot.gif' width='5' border='0' /></td>
                    <td style="text-align: center;">
                        <span class='signs' style='font-size:10px;'>{ROW_DATE}</span>
                    </td>
                    <td><img src='./images/dot.gif' width='5' border='0' /></td>
                </tr>
                <tr>
                    <td><img src='./images/dot.gif' width='5' border='0' /></td>
                    <td style="text-align: center;">{ROW_PHOTO}</td>
                    <td><img src='./images/dot.gif' width='5' border='0' /></td>
                </tr>
                <tr>
                    <td><img src='./images/dot.gif' width='5' border='0' /></td>
                    <td style="text-align: center;">
                        <p style='font-size:10px;text-align:center;'>{ROW_CONTENT}</p>
                    </td>
                    <td><img src='./images/dot.gif' width='5' border='0' /></td>
                </tr>
                <tr>
                    <td colspan='3' style="text-align: center;">
                        <table cellpadding="2" cellspacing="0" style="margin-left: auto; margin-right: auto;">
                            <tr>
                                <td>{ROW_ACTIVELINK}</td>
                                <td>{ROW_EDITLINK}</td>
                                <td>{ROW_DELLINK}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan='3'><img src='./images/dot.gif' height='5' border='0' /></td>
                </tr>
            </table>
            {ROW_DOWN}
        <!-- END: TABLE_ROW -->

        <!-- BEGIN: TABLE_EMPTY -->
        <td>Страниц нет</td>
        <!-- END: TABLE_EMPTY -->

    </tr>
</table>

<table width='100%' border='0' cellspacing='0' cellpadding='0'>
    <tr><td height='12'></td></tr>
    <tr><td align='right'> {MAIN_PAGES} &nbsp; </td></tr>
    <tr><td height='12'></td></tr>
</table>

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->