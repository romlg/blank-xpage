<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<table width='100%' border='0' cellspacing='0' cellpadding='0'>
    <tr><td align='left'>&nbsp;<span class='message'>{MAIN_MESSAGE}</span></td><td align='right'> {MAIN_ADDLINK} &nbsp; </td></tr>
</table>

<table width='100%' border='0' cellspacing='0' cellpadding='2' bgcolor='#F5F5F5' class='w_border'>
    <tr>
        <td class='w_border' width='30'>{HEAD_ORDER}</td>
        <td class='w_border' align='center'>{HEAD_NAME}</td>
        <td class='w_border' align='center'>{HEAD_TITLE}</td>
        <td class='w_border' width='80' colspan='4' align='center'>Действия</td>
    </tr>
    <!-- BEGIN: TABLE_ROW -->
    <tr bgcolor='{ROW_BGCOLOR}'>
        <td class='w_border' align='center'>{ROW_ORDER}</td>
        <td class='w_border'>{ROW_TITLE}</td>
        <td class='w_border'>{ROW_MENU}</td>
        <td class='w_border' align='center' width='20'>{ROW_ORDERLINK}</td>
        <td class='w_border' align='center' width='20'>{ROW_ACTIVELINK}</td>
        <td class='w_border' align='center' width='20'>{ROW_EDITLINK}</td>
        <td class='w_border' align='center' width='20'>{ROW_DELLINK}</td>
    </tr>
    <!-- END: TABLE_ROW -->

    <!-- BEGIN: TABLE_EMPTY -->
    <tr bgcolor='{ROW_BGCOLOR}'>
        <td class='w_border' colspan='7' align='center'>Список пуст</td>
    </tr>
    <!-- END: TABLE_EMPTY -->

</table>

<table width='100%' border='0' cellspacing='0' cellpadding='0'>
    <tr><td height='12'></td></tr>
    <tr><td align='right'> {MAIN_PAGES} &nbsp; </td></tr>
    <tr><td height='12'></td></tr>
</table>

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->