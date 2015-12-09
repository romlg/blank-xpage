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
        $this->mainTemplate = "./templates/reports.tpl";
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $this->pageTitle = "Отчеты";
        $this->pageHeader = "Отчеты";
        
        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
        );
        
        $total = $this->db->GetOne ("Select Count(*) From `reports` Where `is_active`=1", 0);
        if ($total > 0)
        {
            $k = 0;
            $result = $this->db->ExecuteSql ("Select * From `reports` Where `is_active`=1 Order By `title` Asc");
            while ($row = $this->db->FetchInArray ($result))
            {
                $k++;
                $id = $row['report_id'];
                $title = $this->dec ($row['title']);
				$description = nl2br ($this->dec ($row['description']));
				
				$photo = $this->db->GetOne ("Select `photo` From `report_files` Where `report_id`='$id' And `is_active`=1 Order By RAND() LIMIT 1");
          		$img = ($photo != "")? $this->siteUrl."data/report_photos/small_".$photo : $this->siteUrl."images/photo_icon.jpg";
                $photo = "<a href='".$this->siteUrl."report/".$id."'><img src='$img' alt='$title' title='$title' class='img_w_board' /></a>";

                $up = "<td style='width: 33%; text-align: center;'>";
                $down = "</td>";
                
                if ($k == 4)
                {
                    $up = "</tr><tr style='height:10px;'><td colspan='3'></td></tr><tr style='vertical-align: top'><td style='width:33%;' align='center'>";
                    $down = "</td>";
                    $k = 1;
                }
                
                $this->data ['PHOTO_ROW'][] = array (
                    "ROW_UP" => $up,
                    "ROW_DOWN" => $down,
                    
                    "ROW_ID" => $id,
                    "ROW_PHOTO" => $photo,
                    "ROW_CONTENT" => $description,
                    "ROW_TITLE" => $title,
                );
            }
            $this->db->FreeSqlResult ($result);
            
            while ($k < 4)
            {
                $this->data ['PHOTO_ROW'][] = array (
                    "ROW_ID" => "",
                    "ROW_PHOTO" => "",
                    "ROW_CONTENT" => "",
                    "ROW_TITLE" => "",

                    "ROW_UP" => "<td style='width:33%; text-align: center;'>",
                    "ROW_DOWN" => "</td>",
                );
                $k++;
            }
        }
        else
        {
            $this->data ['PHOTO_EMPTY'][] = array (
                "_" => "_"
            );
        }
    }
    
    //--------------------------------------------------------------------------
    function ocd_details ()
    {
        $report_id = $this->GetID ("gid");
        if ($report_id > 0) $this->SaveStateValue ("report_id", $report_id);
        $report_id = $this->GetStateValue ("report_id");

        $report = $this->db->GetEntry ("Select * From `reports` Where `report_id`='$report_id' And `is_active`='1'", "./");
        $title = $this->dec ($report ["title"]);
        $description = nl2br ($this->dec ($report ["description"]));

        $this->javaScripts = $this->GetJavaScript ();
        $this->mainTemplate = "./templates/report_details.tpl";
        $this->pageTitle = "Отчет : ".$title;
        $this->pageHeader = "Отчет : ".$title;

        $back = "<a href='".$this->siteUrl."reports'><img src='".$this->siteUrl."images/back.gif' border='0' alt='Назад к отчетам' title='Назад к отчетам' /></a>";
        $download_link = "";
        if ($report['document'] != "") {
            $download_link = "<p style='text-align: center; padding-bottom: 10px;'>Скачать весь отчет одним файлом: ".
                "<a href='../data/reports/".$report['document']."' target='_blank'>".$report['document']."</a></p>";
        }

        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_C_DESCR" => $description,
            "MAIN_DOWNLOAD_REPORT" => $download_link,
            "BACK" => $back,
        );

        //photos handler
        $total = $this->db->GetOne ("Select Count(*) From `report_files` Where  `report_id`='$report_id' And `is_active`=1", 0);
        if ($total > 0)
        {
            $k = 0;
            $result = $this->db->ExecuteSql ("Select * From `report_files` Where `report_id`='$report_id' And `is_active`=1 Order By `file_id` Asc");
            while ($row = $this->db->FetchInArray ($result))
            {
                $k++;
                $id = $row['file_id'];
                $content = $this->dec ($row['content']);
                $photo = $this->siteUrl."data/report_photos/small_".$this->dec ($row['photo']);
                $photo_big = $this->siteUrl."data/report_photos/".$this->dec ($row['photo']);
                
                $photo = "<a href='$photo_big' rel='gallery' title='$content'><img src='$photo' alt='$content' title='$content' class='img_w_board' /></a>";

                $up = "<td style='width:33%; text-align: center;'>";
                $down = "</td>";

                if ($k == 4)
                {
                    $up = "</tr><tr style='height:10px;'><td colspan='3'></td></tr><tr style='vertical-align: top;'><td style='width: 33%; text-align: center;'>";
                    $down = "</td>";
                    $k = 1;
                }

                $this->data ['PHOTO_ROW'][] = array (
                    "ROW_UP" => $up,
                    "ROW_DOWN" => $down,

                    "ROW_ID" => $id,
                    "ROW_PHOTO" => $photo,
                    "ROW_CONTENT" => $content,
                );
            }
            $this->db->FreeSqlResult ($result);
            
            while ($k < 4)
            {
                $this->data ['PHOTO_ROW'][] = array (
                    "ROW_ID" => "",
                    "ROW_PHOTO" => "",
                    "ROW_CONTENT" => "",

                    "ROW_UP" => "<td style='width:33%; text-align: center;'>",
                    "ROW_DOWN" => "</td>",
                );
                $k++;
            }
        }
        else
        {
            $this->data ['PHOTO_EMPTY'][] = array (
                "_" => "_"
            );
        }
    }

    //--------------------------------------------------------------------------
    function GetJavaScript ()
    {
        return <<<_ENDOFJS_
        <script language='JavaScript' type='text/javascript' src='{$this->siteUrl}js/jquery-1.3.2.min.js'></script>
        <script language='JavaScript' type='text/javascript' src='{$this->siteUrl}js/daGallery-min.js'></script>
_ENDOFJS_;
    }

}

//------------------------------------------------------------------------------

$zPage = new ZPage ("reports");

$zPage->Render ();

?>