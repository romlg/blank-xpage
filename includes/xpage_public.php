<?php

@ini_set ("max_execution_time", 600);
@set_time_limit (600);
@ini_set ("display_errors", "1");
@error_reporting (E_ALL);
@set_magic_quotes_runtime (0);

define ("VALIDATE_NOT_EMPTY", 1);
define ("VALIDATE_USERNAME", 2);
define ("VALIDATE_PASSWORD", 3);
define ("VALIDATE_PASS_CONFIRM", 4);
define ("VALIDATE_EMAIL", 5);
define ("VALIDATE_INT_POSITIVE", 6);
define ("VALIDATE_FLOAT_POSITIVE", 7);
define ("VALIDATE_CHECKBOX", 8);
define ("VALIDATE_NUMERIC", 9);
define ("VALIDATE_NUMERIC_POSITIVE", 10);
define ("VALIDATE_TIME", 11);

define ("FORM_EMPTY", 1);
define ("FORM_FROM_DB", 2);
define ("FORM_FROM_GP", 3);

$db = new XDB ();

class XPage
{
    var $db = null;

    var $mainTemplate;
    var $headerTemplate = "./templates/header.tpl";
    var $footerTemplate = "./templates/footer.tpl";

    var $pageUrl;
    var $pageQueryUrl;
    var $object;    // name of db table
    var $opCode;
    var $defaultCode = "list";

    var $siteUrl = "";
    var $siteTitle = "";
    var $pageTitle = "";
    var $pageHeader = "";
    var $javaScripts = "";

    var $currentPage;
    var $rowsPerPage;
    var $rowsOptions = array (20, 40, 60, 80);

    var $orderBy;
    var $orderDir;
    var $orderDefault;

    var $data;
    var $errors = array ("err_count" => 0);

    var $emailHeader = "";

    var $cartCount = "";
    var $cartCost = "";

    //--------------------------------------------------------------------------
    function XPage ($object = "none", $checkAccess = true)
    {
        @session_start ();

        global $db;
        $this->db = $db;

        $this->sitePath = "/var/www/natalia/data/www/avtostekloservis31.ru/";
        $this->siteUrl = "http://avtostekloservis31.ru/";
        $this->pageUrl = $_SERVER['PHP_SELF'];

        $ip_address = $this->GetServer ("REMOTE_ADDR", "None");
        if ($checkAccess) $this->CheckAccess ();

        $this->siteTitle = $this->db->GetSetting ("a_title", "");
        $adminEmail = $this->adminEmail = $this->db->GetSetting ("a_email", "");
        $this->emailHeader = "From: {$this->siteTitle} <$adminEmail>\r\n";

        $this->mainData = array ();
        $this->pageQueryUrl = $_SERVER['PHP_SELF'].(($_SERVER['QUERY_STRING'] != "") ? "?".$_SERVER['QUERY_STRING'] : "");
        $this->object = $object;
        $this->RestoreState ();
        $this->opCode = $this->GetGP ("ocd", $this->defaultCode);

        $this->data = array ();
    }

    //--------------------------------------------------------------------------
    function HeaderCode ()
    {
        $current_page_id = $this->GetGP ("p_id", 0);
        $current_sub_page_id = $this->GetGP ("s_id", 0);

        $top_menu = "";
        $result = $this->db->ExecuteSql ("Select * From `main_pages` Where is_active=1 And destination=0 Order By order_index Asc");
        while ($row = $this->db->FetchInArray ($result))
        {
            $page_id = $row['page_id'];
            $page_name = $this->dec ($row['menu_title']);
            $is_active = "";
            if ($page_id == $current_page_id) $is_active = "active ";

            $sub_menu = "";
            $result2 = $this->db->ExecuteSql ("Select * From `main_pages` Where is_active=1 And destination='$page_id' Order By order_index Asc");
            while ($row2 = $this->db->FetchInArray ($result2))
            {
                $sub_page_id = $row2['page_id'];
                $sub_page_name = $this->dec ($row2['menu_title']);
                $sub_menu .= "<li class='item{$sub_page_id}'><a href='".$this->siteUrl.$page_id."/$sub_page_id'>$sub_page_name</a></li>";
            }
            $this->db->FreeSqlResult ($result2);
/*
            if ($page_id == 3)
            {
                // Place reports under "О компании"
                $sub_menu .= "<li class='item50'><a href='".$this->siteUrl."reports'>Отчеты</a></li>";
            }
*/
            if ($sub_menu == "")
            {
                $top_menu .= "<li class='".$is_active."item{$page_id}'><a href='".$this->siteUrl.$page_id."'>$page_name</a></li>\r\n";
                if ($page_id == 4)
                {
                    // Place Фотогалереи after Услуги
                    $is_active = (basename ($this->pageUrl) == "galleries.php") ? "active " : "";
                    $top_menu .= "<li class='".$is_active."item60'><a href='".$this->siteUrl."galleries'>Фотогалереи</a></li>\r\n";
                }
            }
            else
            {
                $top_menu .= "<li class='parent ".$is_active."item{$page_id}'><a href='".$this->siteUrl.$page_id."'>$page_name</a><ul>$sub_menu</ul></li>\r\n";
            }
        }
        $this->db->FreeSqlResult ($result);


        $current_sub_page_id = $this->GetGP ("s_id", 0);
        if ($current_sub_page_id > 0)
        {
        	$ceo_page = $current_sub_page_id;
        }
        elseif ($current_page_id > 0)
        {
        	$ceo_page = $current_page_id;
        }
        else
        {
        	$ceo_page = 1;
        }
        $keywords = $this->dec ($this->db->GetOne ("Select `keywords` From `main_pages` Where `page_id`='$ceo_page'", ""));
        $description = $this->dec ($this->db->GetOne ("Select `description` From `main_pages` Where `page_id`='$ceo_page'", ""));

        $datas = array (
            "HEADER_TITLE" => $this->siteTitle." : ".$this->pageTitle,
            "HEADER_JAVASCRIPTS" => $this->javaScripts,
            "HEADER_MAILTO" => $this->adminEmail,
            "HEADER_SITE_NAME" => $this->siteTitle,
            "SITE_URL" => $this->siteUrl,
            "SITE_YEAR" => date ("Y"),

            "TOP_MENU" => $top_menu,

            "KEYWORDS" => $keywords,
            "DESCRIPTION" => $description,
        );

        $total = $this->db->GetOne ("Select Count(*) From `news` Where is_active='1'", 0);
        if ($total > 0)
        {
            $result = $this->db->ExecuteSql ("Select * From `news` Where is_active=1 Order By z_date Desc Limit 4");
            while ($row = $this->db->FetchInArray ($result))
            {
                $article = nl2br ($this->dec ($row['article']));
                $title = $this->dec ($row['title']);
                $date = date ("d.m.Y", $row['z_date']);
                $datas['NEWS_ROW'][] = array (
                    "ROW_ID" => $row['item_id'],
                    "ROW_DATE" => $date,
                    "ROW_TITLE" => $title,
                    "ROW_ARTICLE" => $article,
                );
            }
            $this->db->FreeSqlResult ($result);
        }

        return $datas;
    }

    //--------------------------------------------------------------------------
    // This function prepare array for main part of page
    // Should be re-defined
    function FooterCode ()
    {
        $datas = array (
            "_" => "_",
        );
        return $datas;
    }

    //--------------------------------------------------------------------------
    // Function call required method which should prepare array for main part of page
    function RunController ()
    {
        // execute required method or redirect to default view
        $method = "ocd_".$this->opCode;
        if (method_exists ($this, $method)) {
            $this->$method ();
        }
        else {
            $this->Redirect ($this->pageUrl);
        }
    }

    //--------------------------------------------------------------------------
    // Render a page
    function Render ()
    {
        $this->RunController ();

        $tpl = new XTemplate ($this->mainTemplate);

        $tpl->assign_file ("HEADER_TEMPLATE", $this->headerTemplate);
        $tpl->assign_file ("FOOTER_TEMPLATE", $this->footerTemplate);

        $tpl->assign_array ("MAIN.HEADER", $this->HeaderCode ());
        $tpl->assign_array ("MAIN.FOOTER", $this->FooterCode ());

        if (count ($this->data) > 0) {
            $tpl->assign_array ("MAIN", $this->data);
        }
        else {
            $tpl->assign ("MAIN_HEADER", $this->pageHeader);
            $tpl->parse ("MAIN");
        }

        header ("Content-Type: text/html; charset=utf-8");
        $tpl->out ("MAIN");
        $this->Close ();
    }

    //--------------------------------------------------------------------------
    // Close db connection and free memory etc.
    function Close ()
    {
        $this->db->Close ();
        $this->SaveState ();
    }

//== Authentication section ====================================================

    //--------------------------------------------------------------------------
    function RegisterUser ()
    {
        return true;
    }

     //--------------------------------------------------------------------------
    function CheckAccess ()
    {
        return true;
    }


    //--------------------------------------------------------------------------
    function UpdateRegisterDetails ()
    {
        return true;
    }

    //--------------------------------------------------------------------------
    function Logout ($url = "./index.php")
    {
        $_SESSION ["MemberID"] = 0;
        $this->Redirect ($url);
    }


//== Paging and Sorting support section ========================================

    //--------------------------------------------------------------------------
    function Pages_GetFirstIndex ()
    {
        return $this->currentPage * $this->rowsPerPage;
    }

    //--------------------------------------------------------------------------
    function Pages_GetLastIndex ($total)
    {
        $toRet = $this->Pages_GetFirstIndex() + $this->rowsPerPage;
        if ($toRet > $total) $toRet = $total;
        return $toRet;
    }

    //--------------------------------------------------------------------------
    function Pages_GetLimits ()
    {
        $start = $this->currentPage * $this->rowsPerPage;
        $toRet = " LIMIT $start, {$this->rowsPerPage} ";
        return $toRet;
    }

    //--------------------------------------------------------------------------

    //--------------------------------------------------------------------------
    //--------------------------------------------------------------------------
    function Pages_GetLinks ($totalRows, $link)
    {
        $left = "";
        $right = "";

        $toRet = "<table style='width:99%;' cellspacing='0' cellpadding='0' border='0' align='left'><tr>";

        $totalPages = ceil ($totalRows / $this->rowsPerPage);

        if ($totalPages > 1)
        {
            $toRet .= "<td valign='top' align='left'>";

            for ($i = 0; $i < $totalPages; $i++)
            {
                $start = $i * $this->rowsPerPage + 1;
                $end = $start + $this->rowsPerPage - 1;
                if ($end > $totalRows) $end = $totalRows;
                $pageNo = $left."$start-$end".$right;


                if ($i == $this->currentPage)
                    $pageNo = "<b class='pages'>$pageNo</b>";
                else
                    $pageNo = "<a href='".$link."pg=$i' class='pages'>$pageNo</a>";



                $pageNo = "<table cellspacing='0' cellpadding='0' border='0' style='height:17px; float: left;margin:1px;'>
                    <tr style='height:17px;'>
                        <td style='width:6px;' background='./images/t_left.gif'>
                        </td>
                        <td background='./images/t_center.gif' style='white-space:nowrap;'>
                            $pageNo
                        </td>
                        <td style='width:7px;' background='./images/t_right.gif'>
                        </td>
                    </tr>
                </table> ";
                $toRet .= $pageNo;
            }
        }
        $toRet .= "</td>";
        return $toRet."</tr></table>";

    }

    //--------------------------------------------------------------------------

    function Pages_GetLinks_p ($totalRows, $link)
    {
        $mt = $this->GetID ("id");
        $toRet = "<table width='100%' cellspacing='0' cellpadding='0'><tr>";
        $divider = "&nbsp;&nbsp;";
        $left = "[";
        $right = "]";
        $toRet .= "<td valign='top' align='left'></td>";
        $toRet .= "<td valign='top' align='left'>";
        $totalPages = ceil ($totalRows / 10);
        if ($totalPages > 1)
        {
            $toRet .= "Работы: ";
            for ($i = 0; $i < $totalPages; $i++)
            {
                $start = $i * $this->rowsPerPage + 1;
                $end = $start + $this->rowsPerPage - 1;
                if ($end > $totalRows) $end = $totalRows;
                $pageNo = $left."$start-$end".$right;
                if ($i == $this->currentPage)
                    $toRet .= "$divider<span class='pages'>$pageNo</span>";
                else
                    $toRet .= "$divider<a href='".$link."pg=$i&id=$mt' class='sub_menu'>$pageNo</a>";
            }
        }
        $toRet .= "</td>";

        return $toRet."</tr></table>";
    }

    //--------------------------------------------------------------------------

    function Pages_GetLinks1 ($totalRows, $link)
    {
        $divider = "&nbsp;&nbsp;";
        $left = "[";
        $right = "]";

        $toRet = "<table width='100%' cellspacing='0' cellpadding='0'><tr>";

        $toRet .= "<td valign='top' align='left'>";
        $totalPages = ceil ($totalRows / $this->rowsPerPage);
        if ($totalPages > 1)
        {
            $toRet .= "<b class='pages'> Страницы:</b>";
            for ($i = 0; $i < $totalPages; $i++)
            {
                $start = $i * $this->rowsPerPage + 1;
                $end = $start + $this->rowsPerPage - 1;
                if ($end > $totalRows) $end = $totalRows;
                $pageNo = $left."$start-$end".$right;
                if ($i == $this->currentPage)
                    $toRet .= "$divider<b class='pages'>$pageNo</b>";
                else
                    $toRet .= "$divider<a href='".$link."/$i' class='pages'>$pageNo</a>";
            }
        }
        $toRet .= "</td>";

        return $toRet."</tr></table>";
    }

    //--------------------------------------------------------------------------
    function Header_GetSortLink ($field, $title = "", $page)
    {
        if ($title == "") $title = $field;
        $drctn = ($this->orderDir == "asc") ? "desc" : "asc";
        $toRet = "<a class='pages' href='".$this->siteUrl.$page."/$field/$drctn'>$title</a>";

        if ($field == $this->orderBy) {
            $toRet .= "<img src='".$this->siteUrl."images/sort_{$this->orderDir}.gif' width='10' border='0'>";
        }

        return $toRet;
    }


//== Validation support section ================================================

    //--------------------------------------------------------------------------
    function GetValidGP ($key, $name, $type = VALIDATE_NOT_EMPTY, $defValue = "")
    {
        $value = $defValue;
        if (array_key_exists ($key, $_GET)) $value = trim ($_GET [$key], "\x00..\x20");
        elseif (array_key_exists ($key, $_POST)) $value = trim ($_POST [$key], "\x00..\x20");

        switch ($type)
        {
            case VALIDATE_NOT_EMPTY:
                if ($value == "") {
                    $this->SetError ($key, "Поле '$name' не заполнено.");
                }
                break;

            case VALIDATE_USERNAME:
                if (preg_match ("/^[\w]{4,12}\$/i", $value) == 0) {
                    $this->SetError ($key, "Поле '$name' должно содержать 4 - 12 цифро-буквенных символов.");
                }
                break;

            case VALIDATE_PASSWORD:
                if (preg_match ("/^[\w]{6,12}\$/i", $value) == 0) {
                    $this->SetError ($key, "Поле '$name' должно содержать 6 - 12 цифро-буквенных символов.");
                }
                break;

            case VALIDATE_PASS_CONFIRM:
                if ($value != $name) {
                    $this->SetError ($key, "Пароли не совпадают.");
                }
                break;

            case VALIDATE_EMAIL:
                if (preg_match ("/^[-_\.0-9a-z]+@[-_\.0-9a-z]+\.+[a-z]{2,3}\$/i", $value) == 0) {
                    $this->SetError ($key, "'$name' содержит неправильный формат e-mail адреса.");
                }
                break;

            case VALIDATE_INT_POSITIVE:
                if (!is_numeric ($value) or (preg_match ("/^\d+\$/i", $value) == 0)) {
                    $this->SetError ($key, "Поле '$name' должно содержать положительное целое число.");
                }
                break;

            case VALIDATE_FLOAT_POSITIVE:
                if (!is_numeric ($value) or (preg_match ("/^[\d]+\.+[\d]+\$/i", $value) == 0)) {
                    $this->SetError ($key, "Поле '$name' должно содержать положительное число.");
                }
                break;

            case VALIDATE_CHECKBOX:
                if ($value == $defValue) {
                    $this->SetError ($key, "You must accept '$name'.");
                }
                break;

            case VALIDATE_NUMERIC:
                if (!is_numeric ($value)) {
                    $this->SetError ($key, "Поле '$name' должно быть числовым.");
                }
                break;

            case VALIDATE_NUMERIC_POSITIVE:
                if (!is_numeric ($value) Or $value < 0) {
                    $this->SetError ($key, "Поле '$name' должно содержать положительное число.");
                }
                break;

            case VALIDATE_TIME:

                $array = explode(":", $value);

//                print_r ($array);

                if (Count ($array) == 3)
                {
                    $first = (is_numeric ($array [0]) And ($array [0] <= 24))? true : false;
                    $second = (is_numeric ($array [1]) And ($array [1] <= 59))? true : false;
                    $third = (is_numeric ($array [2]) And ($array [2] <= 59))? true : false;

                    $result = ($first And $second And $third);
                    if (!$result) $this->SetError ($key, "Поле '$name' должно иметь формат 00:00:00 ");

                }
                else
                {
                    $this->SetError ($key, "Поле '$name' должно иметь формат 00:00:00 ");
                }

                break;
        }

        return $value;
    }

    //--------------------------------------------------------------------------
    function SetError ($key, $text)
    {
        $this->errors['err_count']++;
        $this->errors[$key] = $text;
    }

    //--------------------------------------------------------------------------
    function GetError ($key)
    {
        return (array_key_exists ($key, $this->errors)) ? $this->errors[$key] : "";
    }

//== Common functions section ==================================================

    //--------------------------------------------------------------------------
    function Redirect ($targetURL)
    {
        $this->Close ();
        header ("Location: $targetURL");
        exit ();
    }


    //--------------------------------------------------------------------------
    function decap ($value)
    {

        $some = $value * 1000;

        $integer = floor ($some / 1000);
        $fraction = $some / 1000 - $integer;

        if ($fraction == 0)
        {
            return $integer;
        }
        else
        {
            $number = $integer + $fraction;
            $number = str_replace (".", ",", $number);
        }
        return $number;
    }

    //--------------------------------------------------------------------------
    function GetGPC ($key, $defValue = "")
    {
        $toRet = $defValue;
        if (array_key_exists ($key, $_GET)) $toRet = trim ($_GET [$key]);
        elseif (array_key_exists ($key, $_POST)) $toRet = trim ($_POST [$key]);
        elseif (array_key_exists ($key, $_COOKIE)) $toRet = trim ($_COOKIE [$key]);

        return (get_magic_quotes_gpc ()) ? stripslashes ($toRet) : $toRet;
    }

    //--------------------------------------------------------------------------
    function GetGP ($key, $defValue = "")
    {
        $toRet = $defValue;
        if (array_key_exists ($key, $_GET)) $toRet = trim ($_GET [$key]);
        elseif (array_key_exists ($key, $_POST)) $toRet = trim ($_POST [$key]);

        $toRet = str_replace (";", " ", $toRet);

        $toRet = (get_magic_quotes_gpc ()) ? stripslashes ($toRet) : $toRet;

        return $toRet;

    }

    //--------------------------------------------------------------------------
    function enc ($value)
    {
        $search = array ("/</", "/>/", "/'/");
        $replace = array ("&lt;", "&gt;", "&#039;");
        return preg_replace ($search, $replace, $value);
    }

    //--------------------------------------------------------------------------
    function dec ($value)
    {
        $search = array ("/&lt;/", "/&gt;/", "/&#039;/", "/&amp;/");
        $replace = array ("<", ">", "'", "&");

        if (strpos ($value, "[PHOTO_") != false)
        {
            $value = $this->makePhotoSubstitution ($value);
        }

        if (strpos ($value, "[AUDIO_") != false)
        {
            $value = $this->makeAudioSubstitution ($value);
        }

        if (strpos ($value, "[VIDEO_") != false)
        {
            $value = $this->makeVideoSubstitution ($value);
        }

        return preg_replace ($search, $replace, $value);
    }

    //--------------------------------------------------------------------------
    function makePhotoSubstitution ($toRet)
    {


        $result = $this->db->ExecuteSql ("Select * From `photos` Order By `photo_id` Desc");
        while ($row = $this->db->FetchInArray ($result))
        {

            $id = $row['photo_id'];
            $content = $this->dec ($row['content']);
            $photo = "./data/photos/small_".$row['photo'];
            $photo_big = "./data/photos/".$row['photo'];

            $photo = "<a href='$photo_big' target='_blank'><img src='$photo' alt='$content' title='$content' class='site_icon' /></a>";



            $toRet = preg_replace ("/\[PHOTO_".$id."\]/", $photo, $toRet);


        }
        $this->db->FreeSqlResult ($result);

        return $toRet;

    }

    //--------------------------------------------------------------------------
    function makeVideoSubstitution ($toRet)
    {


        $result = $this->db->ExecuteSql ("Select * From `video` Order By `video_id` Desc");
        while ($row = $this->db->FetchInArray ($result))
        {

            $id = $row['video_id'];
            $code = $this->dec ($row['code']);

            $toRet = preg_replace ("/\[VIDEO_".$id."\]/", $code, $toRet);


        }
        $this->db->FreeSqlResult ($result);

        return $toRet;

    }

    //--------------------------------------------------------------------------
    function makeAudioSubstitution ($toRet)
    {


        $result = $this->db->ExecuteSql ("Select * From `audio` Order By `audio_id` Desc");
        while ($row = $this->db->FetchInArray ($result))
        {

            $id = $row['audio_id'];
            $file = $row['file'];

            $file = $this->xgetAudioPlayer ($this->siteUrl, $this->siteUrl."data/audio/".$file);

            $toRet = preg_replace ("/\[AUDIO_".$id."\]/", $file, $toRet);


        }
        $this->db->FreeSqlResult ($result);

        return $toRet;

    }

    //--------------------------------------------------------------------------
    function xgetAudioPlayer ($siteUrl, $file)
    {
        return "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' width='96' height='20' id='own_flashplayer' align='middle'>
            <param name='allowScriptAccess' value='sameDomain' />
            <param name='movie' value='".$siteUrl."player/flashplayer_mem.swf?file=$file&startplay=false' /><param name='quality' value='high' /><param name='bgcolor' value='#ffffff' /><embed src='".$siteUrl."player/flashplayer_mem.swf?file=$file&startplay=false' quality='high' bgcolor='#ffffff' width='96' height='20' name='own_flashplayer' align='middle' allowScriptAccess='sameDomain' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />
            </object>";
    }

    //--------------------------------------------------------------------------
    function GetID ($key)
    {
        $toRet = 0;
        if (array_key_exists ($key, $_GET)) {
            $toRet = trim ($_GET [$key]);
        }
        elseif (array_key_exists ($key, $_POST)) {
            $toRet = trim ($_POST [$key]);
        }
        if (!is_numeric ($toRet)) $toRet = 0;
        return $toRet;
    }

    //--------------------------------------------------------------------------
    function GetSession ($str, $defValue = "")
    {
        $toRet = $defValue;
        if (array_key_exists ($str, $_SESSION)) $toRet = trim ($_SESSION [$str]);
        return $toRet;
    }

    //--------------------------------------------------------------------------
    function GetServer ($key, $defValue = "")
    {
        $toRet = $defValue;
        if (array_key_exists ($key, $_SERVER)) $toRet = trim ($_SERVER [$key]);
        return $toRet;
    }

    //--------------------------------------------------------------------------
    function GetStateValue ($key2, $defValue = "")
    {
        $toRet = $defValue;
        $key1 = "p_".$this->object;
        if (array_key_exists ($key1, $_SESSION)) {
            if (array_key_exists ($key2, $_SESSION[$key1])) {
                $toRet = $_SESSION [$key1][$key2];
            }
        }
        return $toRet;
    }

    //--------------------------------------------------------------------------
    function SaveStateValue ($key2, $value)
    {
        $key1 = "p_".$this->object;
        $_SESSION[$key1][$key2] = $value;
    }

    //--------------------------------------------------------------------------
    function RemoveStateValue ($key2)
    {
        $key1 = "p_".$this->object;
        unset ($_SESSION[$key1][$key2]);
    }

    //--------------------------------------------------------------------------
    function SaveState ()
    {
        $key = "p_".$this->object;
        $_SESSION[$key]['pg'] = $this->currentPage;
        $_SESSION[$key]['rpp'] = $this->rowsPerPage;
        $_SESSION[$key]['order'] = $this->orderBy;
        $_SESSION[$key]['drctn'] = $this->orderDir;
    }

    //--------------------------------------------------------------------------
    function RestoreState ()
    {
        // Get current page index
        $this->currentPage = ($this->GetID ("pg") != "") ? $this->GetID ("pg") : $this->GetStateValue ("pg", 0);
        $this->rowsPerPage = ($this->GetID ("rpp") != "") ? $this->GetID ("rpp") : $this->GetStateValue ("rpp", 20);
        $this->orderBy = ($this->GetGP ("order") != "") ? $this->GetGP ("order") : $this->GetStateValue ("order", $this->orderDefault);
        $this->orderDir = ($this->GetGP ("drctn") != "") ? $this->GetGP ("drctn") : $this->GetStateValue ("drctn", "asc");

        $this->SaveState ();
    }

    //--------------------------------------------------------------------------
    function GetUserAgent ($user_agent)
    {
        $browser = "unknown";
        if (eregi ("Opera", $user_agent)) $broser = "Opera";
        if (eregi ("MSIE", $user_agent)) $browser = "MS Internet Explorer";
        if (eregi ("Netscape", $user_agent)) $browser = "Netscape";
        if (eregi ("Mozilla", $user_agent) and !eregi ("MSIE", $user_agent)) $browser = "Mozilla";

        return $browser;
    }

}


//------------------------------------------------------------------------------
// Database class
class XDB
{
    var $dbConnect;

    //--------------------------------------------------------------------------
    function XDB ()
    {
        // open DB connection
        $this->dbConnect = $this->OpenDbConnect ();
        $this->ExecuteSql ("Set names utf8");
    }

    //--------------------------------------------------------------------------
    function OpenDbConnect ($host = DbHost, $dbName = DbName, $login = DbUserName, $pwd = DbUserPwd)
    {
        $connect = mysql_connect ($host, $login, $pwd);
        mysql_select_db ($dbName);
        return $connect;
    }

    //--------------------------------------------------------------------------
    function ExecuteSql ($sql, $withPaging = false)
    {
        global $zPage;
        if ($withPaging) {
            return mysql_query ($sql.$zPage->Pages_GetLimits(), $this->dbConnect);
        }
        else {
            return mysql_query ($sql, $this->dbConnect);
        }
    }

    //--------------------------------------------------------------------------
    function GetOne ($sql, $defVal = "")
    {
        $toRet = $defVal;
        $result = $this->ExecuteSql ($sql);
        if ($result != false) {
            $line = mysql_fetch_row ($result);
            $toRet = $line[0];
            mysql_free_result ($result);
        }
        if ($toRet == NULL) $toRet = $defVal;
        return $toRet;
    }

    //--------------------------------------------------------------------------
    function GetEntry ($sql, $redir_url = "")
    {
        $result = $this->ExecuteSql ($sql);
        if ($row = $this->FetchInArray ($result))
        {
            $this->FreeSqlResult ($result);
            return $row;
        }
        else
        {
            if (strlen ($redir_url) > 0) {
                $this->Close ();
                header ("Location: $redir_url");
                exit ();
            }
            else {
                return false;
            }
        }
    }

    //--------------------------------------------------------------------------
    function FetchInArray ($result)
    {
        return mysql_fetch_array ($result);
    }

    //--------------------------------------------------------------------------
    function FreeSqlResult ($result)
    {
        mysql_free_result ($result);
    }

    //--------------------------------------------------------------------------
    function GetInsertID ()
    {
        return mysql_insert_id ($this->dbConnect);
    }

    //--------------------------------------------------------------------------
    function GetSetting ($keyname, $defVal = "")
    {
        $toRet = $defVal;
        $result = $this->ExecuteSql ("Select `$keyname` From `settings` Where setting_id='1'");
        if ($result != false) {
            $line = mysql_fetch_row ($result);
            $toRet = $line[0];
            mysql_free_result ($result);
        }
        if ($toRet == NULL) $toRet = $defVal;
        return $toRet;
    }

     //--------------------------------------------------------------------------
    function SetSetting ($keyname, $value)
    {
        $this->ExecuteSql ("Update `settings` Set $keyname='$value' Where setting_id='1'");
    }

    //--------------------------------------------------------------------------
    function Close ()
    {
        mysql_close ($this->dbConnect);
    }

}

?>