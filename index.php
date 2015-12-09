<?php

require_once ("./includes/config.php");
require_once ("./includes/xtemplate.php");
require_once ("./includes/xpage_public.php");

//------------------------------------------------------------------------------

class ZPage extends XPage
{
    //--------------------------------------------------------------------------
    function ZPage ($object)
    {
        XPage::XPage ($object);
        $this->mainTemplate = "./templates/index.tpl";
        
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $row = $this->db->GetEntry ("Select title, content From `main_pages` Where order_index=1");
        $title = $this->dec ($row ['title']);
        $content = $this->dec ($row ['content']);
        $content = str_replace ("{SITE_URL}", $this->siteUrl, $content);
        
        $this->pageTitle = $title;
        $this->pageHeader = $title;
        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_CONTENT" => $content,
        );

    }
}

//------------------------------------------------------------------------------

$zPage = new ZPage ("index");

$zPage->Render ();

?>