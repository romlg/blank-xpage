<?php

@ini_set ("max_execution_time", 6000);
@set_time_limit (6000);
@ini_set ("display_errors", "1");
@error_reporting (E_ALL);
@ini_set ("upload_max_filesize", "24M");
@ini_set ("memory_limit", "128M");
@set_magic_quotes_runtime (0);

//------------------------------------------------------------------------------

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
define ("VALIDATE_INNER_EMAIL", 11);
define ("VALIDATE_FILE", 12);


//------------------------------------------------------------------------------

define ("FORM_EMPTY", 1);
define ("FORM_FROM_DB", 2);
define ("FORM_FROM_GP", 3);

//------------------------------------------------------------------------------

$db = new XDB ();

class XPage
{
    var $db = null;

    var $mainTemplate;
    var $headerTemplate = "./templates/header.tpl";
    var $footerTemplate = "./templates/footer.tpl";

    var $pageUrl;
    var $object;        // can be name of db table
    var $opCode;
    var $defaultCode = "list";

    var $siteTitle = "";
    var $pageTitle = "";
    var $pageHeader = "";
    var $javaScripts = "";

    var $currentPage;
    var $rowsPerPage;
    var $rowsOptions = array (10, 20, 30, 50);

    var $orderBy;
    var $orderDir;
    var $orderDefault;

    var $data;
    var $errors = array ("err_count" => 0);

    var $emailHeader = "";

    //--------------------------------------------------------------------------

    function XPage ($object = "none", $checkAccess = true)
    {
        @session_start ();

        global $db;
        $this->db = $db;

        $this->sitePath = "/public_html/sintezru.com/";
        $this->siteUrl = "http://sintezru.com/";
        $this->pageUrl = $_SERVER['PHP_SELF'];

        $ip_address = $this->GetServer ("REMOTE_ADDR", "None");

        $this->siteTitle = $this->db->GetSetting ("a_title");
        $adminEmail = $this->db->GetSetting ("a_email");
        $this->emailHeader = "From: {$this->siteTitle} <$adminEmail>\r\n";

        // check access rights
        if ($checkAccess) $this->CheckAccess ();

        $this->mainData = array ();

        $this->object = $object;
        $this->RestoreState ();
        $this->opCode = $this->GetGP ("ocd", $this->defaultCode);
        $this->data = array ();
    }

    //--------------------------------------------------------------------------

    function HeaderCode ()
    {
        $siteTitle = $this->siteTitle ;

        $admin_class = (basename ($this->pageUrl) == "admindetails.php")? "menu_invert" : "menu";
        $pages_class = (basename ($this->pageUrl) == "pages.php")? "menu_invert" : "menu";
        $news_class = (basename ($this->pageUrl) == "news.php")? "menu_invert" : "menu";
        $backup_class = (basename ($this->pageUrl) == "backup.php")? "menu_invert" : "menu";
        $gals_class = (basename ($this->pageUrl) == "pgalleries.php" Or basename ($this->pageUrl) == "photos.php")? "menu_invert" : "menu";
		$testimonials_class = (basename ($this->pageUrl) == "testimonials.php")? "menu_invert" : "menu";

        $hdrMenuAdminDetails = "<a href='admindetails.php' class='$admin_class' title='Настройки администратора'>Администратор</a>";
        $hdrMenuPages = "<a href='pages.php' class='$pages_class' title='Страницы сайта'>Страницы сайта</a>";
        $hdrMenuNews = "<a href='news.php' class='$news_class' title='Новости'>Новости</a>";
        $hdrMenuBackup = "<a href='backup.php' class='$backup_class' title='Резервные копии DB'>Резервные копии DB</a>";
        $hdrMenuPhotos = "<a href='pgalleries.php' class='$gals_class' title='Фотогалереи'>Фотогалереи</a>";
		$hdrMenuTestimonials = "<a href='testimonials.php' class='$testimonials_class' title='Отзывы'>Отзывы</a>";
        $hdrMenuReports = "<a href='reports.php' class='$gals_class' title='Отчеты'>Отчеты</a>";

        $hdrMenuLogout = "<a onсlick=\"return confirm ('Вы действительно хотите завершить работу?');\" href='logout.php' class='menu' title='Выход'>Выход</a>";

        $datas = array (
            "HEADER_TITLE" => $siteTitle." : ".$this->pageTitle,
            "HEADER_SITE" => $siteTitle,
            "HEADER_PAGE" => $this->pageTitle,
            "HEADER_JAVASCRIPTS" => $this->javaScripts,
            "HEADER_SERVER_TIME" => date ('M d Y H:i:s'),
            "MENU_ADMINDETAILS" => $hdrMenuAdminDetails,
            "MENU_PAGES" => $hdrMenuPages,
            "MENU_NEWS" => $hdrMenuNews,
            "MENU_REPORTS" => $hdrMenuReports,
            "MENU_PHOTOS" => $hdrMenuPhotos,
			"MENU_TESTIMONIALS" => $hdrMenuTestimonials,

            "MENU_BACKUP" => $hdrMenuBackup,
            "MENU_LOGOUT" => $hdrMenuLogout,
        );
        return $datas;
    }

    //--------------------------------------------------------------------------
    // This function prepare array for main part of page
    // Should be re-defined
    function FooterCode ()
    {
        $datas = array (
            "FOOTER_COPYRIGHT" => "Copyright",
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

        $tpl->assign ($this->HeaderCode ());
        $tpl->parse ("MAIN.HEADER");

        $tpl->assign ($this->FooterCode ());
        $tpl->parse ("MAIN.FOOTER");

        $tpl->assign_array ("MAIN", $this->data);

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
        $login  = $this->GetGP ("login");
        $passwd = md5 ($this->GetGP ("password"));
        $real_login = $this->db->GetSetting ("a_username", "");
        $real_passwd = $this->db->GetSetting ("a_password", "");

        if ($login == $real_login and $passwd == $real_passwd)
        {
            $_SESSION['S_Login'] = $login;
            $_SESSION['S_Passwd'] = $passwd;

            return 1;
        }
        else
        {
            return -1;
        }
    }

    //--------------------------------------------------------------------------
    function CheckAccess ()
    {

        $login = $this->GetSession ("S_Login", "");
        $passwd = $this->GetSession ("S_Passwd", "");
        $real_login = $this->db->GetSetting ("a_username", "");
        $real_passwd = $this->db->GetSetting ("a_password", "");

//        print "$login + $real_login + $passwd + $real_passwd";

        if ($login != $real_login or $passwd != $real_passwd) {
            $this->Logout ();
        }
    }

    //--------------------------------------------------------------------------
    function UpdateRegisterDetails ()
    {
        $_SESSION['S_Login'] = $this->db->GetSetting ("a_username", "");
        $_SESSION['S_Passwd'] = $this->db->GetSetting ("a_password", "");
    }

    //--------------------------------------------------------------------------
    function Logout ()
    {
        $_SESSION = array ();
        session_destroy ();
        $this->Redirect ("./login.php");
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
    function Pages_GetLinks ($totalRows, $link)
    {
        $left = "";
        $right = "";

        $toRet = "<hr /><table style='width:99%;' cellspacing='0' cellpadding='0' border='0' align='left'><tr>";

        $toRet .= "<td valign='top' align='left' style='width:220px;'>
                <table cellspacing='0' cellpadding='0' border='0' style='height:17px;margin:1px;' align='left'>
                    <tr style='height:17px;'>
                        <td>
                            <b class='pages'>Записей на странице:</b>
                        </td>
                    </tr>
                </table>";
        foreach ($this->rowsOptions as $val) {
            $number = ($val == $this->rowsPerPage) ? "<b class='pages'>{$val}</b>" : "<a href='{$link}rpp=$val&pg=0' class='pages'>$val</a>";
            $toRet .= "<table cellspacing='0' cellpadding='0' border='0' style='height:17px;margin:1px;' align='left'>
                    <tr style='height:17px;'>
                        <td style='width:6px;' background='./images/t_left.gif'>
                        </td>
                        <td background='./images/t_center.gif'>
                            $number
                        </td>
                        <td style='width:7px;' background='./images/t_right.gif'>
                        </td>
                    </tr>
                </table>";
        }
        $toRet .= "</td>";

        $toRet .= "<td valign='top' align='left' style='width:120px;'>";
        $totalPages = ceil ($totalRows / $this->rowsPerPage);

        if ($totalPages > 1)
        {
            $toRet .= "<table cellspacing='0' cellpadding='0' border='0' style='height:17px;margin:1px;' align='right'>
                    <tr style='height:17px;'>
                        <td align='right'>
                            <b class='pages'>Перейти к записям:</b>
                        </td>
                    </tr>
                </table>";
            $toRet .= "</td>";
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
    function Header_GetSortLink ($field, $title = "")
    {
        if ($title == "") $title = $field;
        $drctn = ($this->orderDir == "asc") ? "desc" : "asc";
        $toRet = "<a class='menu' href='{$this->pageUrl}?order=$field&drctn=$drctn'><b>$title</b></a>";

        if ($field == $this->orderBy) {
            $toRet .= "<img src='./images/sort_{$this->orderDir}.gif' width='10' border='0'>";
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
                    $this->SetError ($key, "Вам нужно указать '$name'.");
                }
                break;

            case VALIDATE_USERNAME:
                if (preg_match ("/^[\w]{4,12}\$/i", $value) == 0) {
                    $this->SetError ($key, "'$name' должен содержать от 4 до 12 символов или цифр.");
                }
                break;

            case VALIDATE_PASSWORD:
                if (preg_match ("/^[\w]{4,12}\$/i", $value) == 0) {
                    $this->SetError ($key, "'$name' должен содержать от 4 до 12 символов или цифр.");
                }
                break;

            case VALIDATE_FILE:
                if (preg_match ("/^[\w]{4,20}\$/i", $value) == 0) {
                    $this->SetError ($key, "'$name' has to consist of from 4 up to 20 alphanumerical characters.");
                }
                break;

            case VALIDATE_PASS_CONFIRM:
                if ($value != $name) {
                    $this->SetError ($key, "Пароли не совпадают.");
                }
                break;

            case VALIDATE_EMAIL:
                if (preg_match ("/^[-_\.0-9a-z]+@[-_\.0-9a-z]+\.+[a-z]{2,4}\$/i", $value) == 0) {
                    $this->SetError ($key, "'$name' имеет неправильный формат e-mail адреса.");
                }
                break;

            case VALIDATE_INT_POSITIVE:
                if (!is_numeric ($value) or (preg_match ("/^\d+\$/i", $value) == 0)) {
                    $this->SetError ($key, "Поле '$name' должно содержать положительное целое число.");
                }
                break;

            case VALIDATE_FLOAT_POSITIVE:
                if (!is_numeric ($value) or (preg_match ("/^[\d]+\.+[\d]+\$/i", $value) == 0)) {
                    $this->SetError ($key, "Поле '$name' должно содержать положительное число в формате 12.34.");
                }
                break;

            case VALIDATE_CHECKBOX:
                if ($value == $defValue) {
                    $this->SetError ($key, "Вы должны принять '$name'.");
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
    function enc ($value)
    {
        $search = array ("/</", "/>/", "/'/");
        $replace = array ("&lt;", "&gt;", "&#039;");
        return preg_replace ($search, $replace, $value);
    }

    //--------------------------------------------------------------------------
    function dec ($value)
    {
        $search = array ("/&lt;/", "/&gt;/", "/&#039;/");
        $replace = array ("<", ">", "'");
        return preg_replace ($search, $replace, $value);
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
        if (array_key_exists ($key, $_GET)) $toRet = (is_array($_GET [$key])) ? $_GET [$key] : trim ($_GET [$key]);
        elseif (array_key_exists ($key, $_POST)) $toRet = (is_array($_POST [$key])) ? $_POST [$key] : trim ($_POST [$key]);
        return (get_magic_quotes_gpc ()) ? stripslashes ($toRet) : $toRet;
    }


    //--------------------------------------------------------------------------
    function GetGPArray ($key, $defValue = "")
    {
        $toRet = $defValue;
        if (array_key_exists ($key, $_GET)) $toRet = $_GET [$key];
        elseif (array_key_exists ($key, $_POST)) $toRet = $_POST [$key];
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
        $key1 = "a_".$this->object;
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
        $key1 = "a_".$this->object;
        $_SESSION[$key1][$key2] = $value;
    }

    //--------------------------------------------------------------------------
    function RemoveStateValue ($key2)
    {
        $key1 = "a_".$this->object;
        unset ($_SESSION[$key1][$key2]);
    }

    //--------------------------------------------------------------------------
    function SaveState ()
    {
        $key = "a_".$this->object;
        $_SESSION[$key]['pg'] = $this->currentPage;
        $_SESSION[$key]['rpp'] = $this->rowsPerPage;
        $_SESSION[$key]['order'] = $this->orderBy;
        $_SESSION[$key]['drctn'] = $this->orderDir;
    }

    //--------------------------------------------------------------------------
    function RestoreState ()
    {
        // Get current page index
        $this->currentPage = ($this->GetGP ("pg") != "") ? $this->GetGP ("pg") : $this->GetStateValue ("pg", 0);
        $this->rowsPerPage = ($this->GetGP ("rpp") != "") ? $this->GetGP ("rpp") : $this->GetStateValue ("rpp", 20);
        $this->orderBy = ($this->GetGP ("order") != "") ? $this->GetGP ("order") : $this->GetStateValue ("order", $this->orderDefault);
        $this->orderDir = ($this->GetGP ("drctn") != "") ? $this->GetGP ("drctn") : $this->GetStateValue ("drctn", "asc");

        $this->SaveState ();
    }

    //--------------------------------------------------------------------------
    function getUserAgent ($user_agent)
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
// XDB - MySQL Database class
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
            $this->FreeSqlResult ($result);
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