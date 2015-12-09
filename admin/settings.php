<?php

require_once ("../includes/config.php");
require_once ("../includes/xtemplate.php");
require_once ("../includes/xpage_admin.php");

//------------------------------------------------------------------------------

class ZPage extends XPage
{
    //--------------------------------------------------------------------------
    function ZPage ($object)
    {
        $this->orderDefault = "Title";
        XPage::XPage ($object);

        $this->mainTemplate = "./templates/settings.tpl";
        $this->pageTitle = "Настройки сайта";
        $this->pageHeader = "Настройки сайта";
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $ec = $this->GetGP ("ec");
        $message = ($ec == "yes")? "Настройки Вашего сайта были успешно обновлены" : "";
        $siteTitle = $this->db->GetSetting ("SportSiteTitle");
        $siteTitle = "<input type='text' name='SiteTitle' value='$siteTitle' style='width:500px;'>";
        $siteUrl = $this->db->GetSetting ("SportSiteUrl");
        $siteUrl = "<input type='text' name='SiteUrl' value='$siteUrl' style='width:500px;'>";
        $pathSite = $this->db->GetSetting ("SportPathSite");
        $pathSite = "<input type='text' name='PathSite' value='$pathSite' style='width:500px;'>";

        $this->data = array (
            "ACTION_SCRIPT" => $this->pageUrl,
            "MAIN_MESSAGE" => $message,
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_SITE_TITLE" => $siteTitle,
            "MAIN_SITE_URL" => $siteUrl,
            "MAIN_PATH_SITE" => $pathSite,
        );
    }

    //--------------------------------------------------------------------------
    function ocd_update ()
    {
        $siteTitle = $this->enc ($this->GetValidGP ("SiteTitle", "Заголовок сайта", VALIDATE_NOT_EMPTY));
        $siteUrl = $this->GetValidGP ("SiteUrl", "URL сайта", VALIDATE_NOT_EMPTY);
        $pathSite = $this->GetValidGP ("PathSite", "Физический путь к сайту", VALIDATE_NOT_EMPTY);

        if ($this->errors['err_count'] > 0)
        {
            $this->data = array (
                "ACTION_SCRIPT" => $this->pageUrl,
                "MAIN_HEADER" => $this->pageHeader,
                "MAIN_SITE_TITLE" => "<input type='text' name='SiteTitle' value='$siteTitle' style='width:500px;'>",
                "MAIN_SITE_TITLE_ERROR" => $this->GetError ("SiteTitle"),
                "MAIN_SITE_URL" => "<input type='text' name='SiteUrl' value='$siteUrl' style='width:500px;'>",
                "MAIN_SITE_URL_ERROR" => $this->GetError ("SiteUrl"),
                "MAIN_PATH_SITE" => "<input type='text' name='PathSite' value='$pathSite'style='width:500px;'>",
                "MAIN_PATH_SITE_ERROR" => $this->GetError ("PathSite"),
            );
        }
        else {
            if (substr ($siteUrl, -1) != "/") $siteUrl = $siteUrl."/";
            if (substr ($pathSite, -1) != "/") $pathSite = $pathSite."/";

            $this->db->ExecuteSql ("Update settings Set value='$siteTitle' Where keyname='SportSiteTitle'");
            $this->db->ExecuteSql ("Update settings Set value='$siteUrl' Where keyname='SportSiteUrl'");
            $this->db->ExecuteSql ("Update settings Set value='$pathSite' Where keyname='SportPathSite'");
            $this->Redirect ($this->pageUrl."?ec=yes");
        }
    }
}

//------------------------------------------------------------------------------

$zPage = new ZPage ("settings");

$zPage->Render ();

?>