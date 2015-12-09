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
        $this->mainTemplate = "./templates/map.tpl";
        $this->pageTitle = "Карта сайта";
        $this->pageHeader = "Карта сайта";
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        
        $main_news = $main_pages = $main_countries = "";
        
        $total = $this->db->GetOne ("Select Count(*) From `main_pages` Where `destination`='0' And `is_active`=1", 0);
        if ($total > 0)
        {
            $result = $this->db->ExecuteSql ("Select * From `main_pages` Where `destination`='0' And `is_active`=1 Order By `order_index` Asc");
            while ($row = $this->db->FetchInArray ($result))
            {
                $page_id = $row['page_id'];
                $title = $this->dec ($row['title']);
                $main_pages .= "<tr><td style='vertical-align:bottom;padding:2px;padding-left:20px;'><img src='./images/list.gif' valign='bottom' alt='' />  <a href='".$this->siteUrl."content/$page_id' class='newsLink'>$title</a></td></tr>";
                
                $total2 = $this->db->GetOne ("Select Count(*) From `main_pages` Where `destination`='$page_id' And `is_active`=1", 0);
                if ($total2 > 0)
                {
                    $result2 = $this->db->ExecuteSql ("Select * From `main_pages` Where `destination`='$page_id' And `is_active`=1 Order By `order_index` Asc");
                    while ($row2 = $this->db->FetchInArray ($result2))
                    {
                    
                        $page_id2 = $row2['page_id'];
                        $title2 = $this->dec ($row2['title']);
                        $main_pages .= "<tr><td style='vertical-align:bottom;padding:2px;padding-left:40px;'><img src='./images/list.gif' valign='bottom' alt='' />  <a href='".$this->siteUrl."content/$page_id/$page_id2' class='newsLink'>$title2</a></td></tr>";
                    }
                    $this->db->FreeSqlResult ($result2);
                }
            }
            $this->db->FreeSqlResult ($result);
        }
        
        $total = $this->db->GetOne ("Select Count(*) From `news` Where `is_active`='1'", 0);
        if ($total > 0)
        {
            $result = $this->db->ExecuteSql ("Select * From `news` Where `is_active`='1' Order By `z_date` Desc");
            while ($row = $this->db->FetchInArray ($result))
            {
                $item_id = $row['item_id'];
                $title = $this->dec ($row['title']);
                $date =  date ("d.m.Y", $row['z_date']);
                
                $main_news .= "<tr><td style='vertical-align:bottom;padding:2px;padding-left:20px;'><img src='./images/list.gif' valign='bottom' alt='' />  <a href='".$this->siteUrl."news/$item_id' class='newsLink'>$date : $title</a></td></tr>";
            }
            $this->db->FreeSqlResult ($result);
        }
        
        $total = $this->db->GetOne ("Select Count(*) From `pgalleries` Where `is_active`='1'", 0);
        if ($total > 0)
        {
            $result = $this->db->ExecuteSql ("Select * From `pgalleries` Where `is_active`='1' Order By `title` ASC");
            while ($row = $this->db->FetchInArray ($result))
            {
                $item_id = $row['gallery_id'];
                $title = $this->dec ($row['title']);
                
                $main_countries .= "<tr><td style='vertical-align:bottom;padding:2px;padding-left:20px;'><img src='./images/list.gif' valign='bottom' alt='' />  <a href='".$this->siteUrl."country/$item_id' class='newsLink'>$title</a> / <a href='".$this->siteUrl."report/$item_id' class='newsLink'>Фоторепортаж</a></td></tr>";
            }
            $this->db->FreeSqlResult ($result);
        }
        
        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_PAGES" => $main_pages,
            "MAIN_NEWS" => $main_news,
            "MAIN_COUNTRIES" => $main_countries,
             "URL" => $this->siteUrl,
        );

        
    }
}

//------------------------------------------------------------------------------

$zPage = new ZPage ("map");

$zPage->Render ();

?>