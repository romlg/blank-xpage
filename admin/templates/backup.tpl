<!-- BEGIN: MAIN -->

{FILE {HEADER_TEMPLATE}}

<table width='100%' border='0' cellspacing='0' cellpadding='0'>
    <tr><td align='left'>&nbsp;<span class='message'>{MAIN_MESSAGE}</span></td><td align='right'> {MAIN_ADDLINK} &nbsp; </td></tr>
</table>

<table width='100%' border='0' cellspacing='0' cellpadding='2' bgcolor='#F5F5F5' class='w_border'>
    <tr>
        <td class='w_border'>&nbsp;&nbsp;{HEAD_TITLE}</td>
        <td class='w_border' align='center' width='60' colspan='3'><b>Действия</b></td>
    </tr>

    <!-- BEGIN: TABLE_ROW -->
    <tr bgcolor='{ROW_BGCOLOR}'>
        <td class='w_border'>{ROW_TITLE}</td>
        <td class='w_border' align='center' width='20'>{ROW_DOWNLINK}</td>
        <td class='w_border' align='center' width='20'>{ROW_RECOVERLINK}</td>
        <td class='w_border' align='center' width='20'>{ROW_DELLINK}</td>
    </tr>
    <!-- END: TABLE_ROW -->

    <!-- BEGIN: TABLE_EMPTY -->
    <tr bgcolor='{ROW_BGCOLOR}'>
        <td class='w_border' colspan='4' align='center'>Список пуст</td>
    </tr>
    <!-- END: TABLE_EMPTY -->

</table> 

{FILE {FOOTER_TEMPLATE}}

<!-- END: MAIN -->