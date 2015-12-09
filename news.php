<?php

require_once ("./includes/config.php");
require_once ("./includes/xtemplate.php");
require_once ("./includes/xpage_public.php");
require_once ("./includes/utilities.php");

//------------------------------------------------------------------------------

class ZPage extends XPage
{
    //--------------------------------------------------------------------------
    function ZPage ($object)
    {
        XPage::XPage ($object);
        $this->mainTemplate = "./templates/news.tpl";
        $this->pageTitle = "Новости в деталях";
        $this->pageHeader = "Новости в деталях";
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $news_id = $this->GetGP ("n_id");
        $row = $this->db->GetEntry ("Select * From `news` Where item_id='$news_id'", "/.index.php");

        $site = $this->siteUrl;
        $photo = "";
        if (strlen ($row['photo']) > 0)
        {
            $photo = "<a href='".$site."data/news/".$row['photo'].".jpg' target='_blank'><img class='img_w_board' src='".$site."data/news/small_".$row['photo'].".jpg' border='0' align='left' vspace='6' hspace='4'></a>&nbsp;&nbsp;";
        }

        $photo2 = "";
        if (strlen ($row['photo2']) > 0)
        {
            $photo2 = "<a href='".$site."data/news/".$row['photo2'].".jpg' target='_blank'><img class='img_w_board' src='".$site."data/news/small_".$row['photo2'].".jpg' border='0' align='left' vspace='6' hspace='4'></a>&nbsp;&nbsp;";
        }


        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_DATE" => date ("d.m.Y", $row['z_date']),
            "MAIN_PHOTO" => $photo,
            "MAIN_PHOTO2" => $photo2,
            "MAIN_ARTICLE" => nl2br ($this->dec ($row['article'])),
            "MAIN_TITLE" => $this->dec ($row['title']),
            "MAIN_DESCRIPTION" => $this->dec ($row['description']),
            "MAIN_ID" => $news_id,
        );

    }
 
}

//------------------------------------------------------------------------------

$zPage = new ZPage ("index");

$zPage->Render ();

?>