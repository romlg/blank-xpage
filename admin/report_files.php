<?php

require_once ("../includes/config.php");
require_once ("../includes/xtemplate.php");
require_once ("../includes/xpage_admin.php");
require_once ("../includes/utilities.php");

//------------------------------------------------------------------------------

class ZPage extends XPage
{

    //--------------------------------------------------------------------------
    function ZPage ($object)
    {
        XPage::XPage ($object);
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $report_id  = $this->GetGP ("id", 0);
        $this->mainTemplate = "./templates/report_files.tpl";

        if ($report_id > 0) {
            $this->SaveStateValue ("report_id", $report_id);
        }
        else {
           $report_id = $this->GetStateValue ("report_id", 0);
        }
        $c_title = $this->db->GetOne ("Select title From `reports` Where report_id='$report_id'");

        $this->pageHeader = "Страницы отчета '$c_title'";
        $this->pageTitle = "Страницы отчета '$c_title'";

        $total = $this->db->GetOne ("Select Count(*) From `{$this->object}` Where report_id='$report_id'", 0);

        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_ADDLINK" => "<a href='{$this->pageUrl}?ocd=new' title='Добавить страницу'><img src='./images/add.gif' /></a>",
            "MAIN_PAGES" => $this->Pages_GetLinks ($total, $this->pageUrl."?"),
        );

        $bgcolor = "";
        if ($total > 0)
        {
            $result = $this->db->ExecuteSql ("Select * From `{$this->object}` Where report_id='$report_id' Order By file_id Asc", true);
            $k = 0;
            while ($row = $this->db->FetchInArray ($result))
            {
                $k += 1;
                $id = $row ['file_id'];
                $content = nl2br ($this->dec ($row['content']));
                $z_date = $row['z_date'];
                $photo = ($row['photo'] != "") ? "<a href='../data/report_photos/".$row['photo']."' target='_blank'><img class='img_w_border' title='Увеличить' src='../data/report_photos/small_".$row['photo']."'></a><br />" : "";
                $up = "<td style='width:20%; text-align: center;'>";
                $down = "</td>";
                $activeLink = "<a href='{$this->pageUrl}?ocd=activate&id=$id'><img src='./images/active".$row['is_active'].".gif' alt='Изменить статус активности' title='Изменить статус активности' /></a>";
                $editLink = "<a href='{$this->pageUrl}?ocd=edit&id=$id'><img src='./images/edit.gif' alt='Режим редактирования' title='Режим редактирования' /></a>";
                $delLink = "<a href='{$this->pageUrl}?ocd=del&id=$id' onclick=\"return confirm ('Вы действительно хотите удалить эту страницу?');\"><img src='./images/garbage.gif' alt='Удалить' title='Удалить' /></a>";

                if ($k == 6)
                {
                    $up = "</tr><tr style='vertical-align: top;'><td style='width:20%; text-align: center;'>";
                    $down = "</td>";
                    $k = 0;
                }

                $this->data ['TABLE_ROW'][] = array (
                    "ROW_ID" => $id,
                    "ROW_CONTENT" => $content,
                    "ROW_PHOTO" => $photo,
                    "ROW_DATE" => date ('d-m-Y', $z_date),
                    "ROW_UP" => $up,
                    "ROW_DOWN" => $down,

                    "ROW_ACTIVELINK" => $activeLink,
                    "ROW_EDITLINK" => $editLink,
                    "ROW_DELLINK" => $delLink,
                );
            }
            $this->db->FreeSqlResult ($result);
            
            while ($k < 6)
            {
                $this->data ['TABLE_ROW'][] = array (
                    "ROW_ID" => "",
                    "ROW_CONTENT" => "",
                    "ROW_PHOTO" => "",
                    "ROW_DATE" => "",
                    "ROW_ACTIVELINK" => "",
                    "ROW_EDITLINK" => "",
                    "ROW_DELLINK" => "",

                    "ROW_UP" => "<td style='width:20%; text-align: center;'>",
                    "ROW_DOWN" => "</td>",
                );
                $k++;
            }
        }
        else
        {
            $bgcolor = ($bgcolor == "") ? "#E7E7E7" : "";
            $this->data ['TABLE_EMPTY'][] = array (
                "ROW_BGCOLOR" => $bgcolor
            );
        }
    }

    //--------------------------------------------------------------------------
    function fill_form ($opCode = "insert", $source = FORM_EMPTY)
    {
        $this->mainTemplate = "./templates/report_file_details.tpl";
        $id = $this->GetGP ("id", 0);

        $physical_path = $this->sitePath;
        $site_URL = $this->siteUrl;

        switch ($source)
        {
            case FORM_FROM_DB:
                $row = $this->db->GetEntry ("Select * From `{$this->object}` Where file_id='$id'", $this->pageUrl);
                $z_date = getDaySelect (date ("d", $row["z_date"]), "dateDay")." ".getMonthSelect (date ("m", $row["z_date"]), "dateMonth")." ".getYearSelect (date ("Y", $row["z_date"]), "dateYear");
                $content = "<textarea rows='4' style='width: 400px;' name='content' class='many_lines'>".$row ["content"]."</textarea>";
                $gallery = $this->selectGallery ($row ["report_id"]);
                $photo = "";
                if ($row["photo"] != "" And file_exists ($physical_path."data/report_photos/".$row["photo"]))
                {
                    $photo = "<a href='../data/report_photos/".$row["photo"]."' target='_blank'><img title='Увеличить' class='img_w_border' src='../data/report_photos/small_".$row["photo"]."' /></a>&nbsp;";
                    $photo .= "<a href='{$this->pageUrl}?ocd=delphoto&id=$id' onClick=\"return confirm ('Вы действительно хотите удалить эту страницу?')\"><img src='./images/garbage.gif' alt='Удалить' title='Удалить' /></a>";
                }
                else
                {
                    $photo = "<input type='file' name='photo' value='' style='width: 320px;' />";
                }
                break;

            case FORM_FROM_GP:

                $z_date = getDaySelect ($this->GetGP ("dateDay"), "dateDay")." ".getMonthSelect ($this->GetGP ("dateMonth"), "dateMonth")." ".getYearSelect ($this->GetGP ("dateYear"), "dateYear");
                $content = "<textarea rows='4' style='width: 400px;' name='content' class='many_lines'>".$this->GetGP ("content", 0)."</textarea>";
                $gallery = $this->selectGallery ($this->GetGP ("report_id", 0));

                $photo = "";
                $photo_file = $this->db->GetOne ("Select photo From `{$this->object}` Where file_id='$id'");
                if ($photo_file != "" And file_exists ($physical_path."data/report_photos/".$photo_file) And $opCode == "update")
                {
                    $photo = "<a href='".$site_URL."data/report_photos/".$photo_file."' target='_blank'><img title='Увеличить' class='img_w_border' src='../data/report_photos/small_".$photo_file."'></a>&nbsp;";
                    $photo .= "<a href='{$this->pageUrl}?ocd=delphoto&id=$id' onClick=\"return confirm ('Вы действительно хотите удалить эту страницу?')\"><img src='./images/garbage.gif' alt='Удалить' title='Удалить' /></a>";
                }
                else
                {
                    $photo = "<input type='file' name='photo' value='' style='width: 320px;' />";
                }
                break;

            case FORM_EMPTY:
            default:
                $z_date = getDaySelect ("", "dateDay")." ".getMonthSelect ("", "dateMonth")." ".getYearSelect ("", "dateYear");
                $content = "<textarea rows='4' style='width: 400px;' name='content' class='many_lines'></textarea>";
                $photo = "<input type='file' name='photo' value='' style='width: 320px;' />";
                $gallery = $this->selectGallery ($this->GetStateValue ("report_id", 0));
                break;
        }

        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_ACTION" => $this->pageUrl,

            "MAIN_DATE" => $z_date,
            "MAIN_CONTENT" => $content,
            "MAIN_CONTENT_ERROR" => $this->GetError ("content"),
            "MAIN_PHOTO" => $photo,
            "MAIN_GALLERY" => $gallery,

            "MAIN_CANCEL_URL" => $this->pageUrl,
            "MAIN_OCD" => $opCode,
            "MAIN_ID" => $id,
        );
    }

    //--------------------------------------------------------------------------
    function selectGallery ($value = 0)
    {
        $toRet = "<select name='report_id' style='width:300px;' class='select_line'> \r\n";
        $result = $this->db->ExecuteSql ("Select * From `reports` Order By title Asc");
        while ($row = $this->db->FetchInArray ($result))
        {
            $selected = ($row['report_id'] == $value) ? "selected" : "";
            $toRet .= "<option value='".$row['report_id']."' $selected>".$row['title']."</option>";
        }
        return $toRet."</select>\r\n";
    }

    //--------------------------------------------------------------------------
    function ocd_new ()
    {
        $report_id = $this->GetStateValue ("report_id", 0);
        $c_title = $this->db->GetOne ("Select title From `reports` Where report_id='$report_id'", "");
        $this->pageHeader = "Страницы отчета '$c_title' : режим добавления";
        $this->pageTitle = "Страницы отчета '$c_title' : режим добавления";

        $this->fill_form ("insert", FORM_EMPTY);
    }

    //--------------------------------------------------------------------------
    function ocd_insert ()
    {
        $report_id = $this->GetStateValue ("report_id", 0);
        $c_title = $this->db->GetOne ("Select title From `reports` Where report_id='$report_id'");
        $this->pageHeader = "Страницы отчета '$c_title' : режим добавления";
        $this->pageTitle = "Страницы отчета '$c_title' : режим добавления";

        $z_date = mktime (0, 0, 0, $this->GetGP ("dateMonth"), $this->GetGP ("dateDay"), $this->GetGP ("dateYear"));
        $content = $this->enc ($this->GetValidGP ("content", "Описание", VALIDATE_NOT_EMPTY));

        if ($this->errors['err_count'] > 0)
        {
            $this->fill_form ("insert", FORM_FROM_GP);
        }
        else
        {
            if (array_key_exists ("photo", $_FILES) and $_FILES['photo']['error'] < 3)
            {
                $symbs = getUnID (5);
                $oldname = $_FILES['photo']['name'];
                $tmp_name = $_FILES['photo']['tmp_name'];
                $short_name = $report_id."_".$symbs;
                $new_name = $short_name.".jpg";
                $thumb_name = "small_".$new_name;
                if (is_uploaded_file ($tmp_name))
                {
                    $physical_path = $this->sitePath;
                    move_uploaded_file ($tmp_name, $physical_path."data/report_photos/".$new_name);

                    $cmd = "chmod 666 ".$physical_path."data/report_photos/".$new_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/report_photos/".$new_name, 0777);
                    copy ($physical_path."data/report_photos/".$new_name, $physical_path."data/report_photos/".$thumb_name);
                    $cmd = "chmod 666 ".$physical_path."data/report_photos/".$thumb_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/report_photos/".$thumb_name, 0777);
                    
                    makeThumbnail ($physical_path."data/report_photos/".$thumb_name, 0);
                    makeThumbnail ($physical_path."data/report_photos/".$new_name, 1);
                    
                    $this->db->ExecuteSql ("Insert into `{$this->object}` (report_id, content, z_date, photo, is_active) Values ('$report_id', '$content', '$z_date', '$new_name', 0)");
                }
            }
            $this->Redirect ($this->pageUrl);
        }
    }

    //--------------------------------------------------------------------------
    function ocd_edit ()
    {
        $report_id = $this->GetStateValue ("report_id", 0);
        $c_title = $this->db->GetOne ("Select title From `reports` Where report_id='$report_id'");
        $this->pageHeader = "Страницы отчета '$c_title' : режим редактирования";
        $this->pageTitle = "Страницы отчета '$c_title' : режим редактирования";

        $this->fill_form ("update", FORM_FROM_DB);
    }

    //--------------------------------------------------------------------------
    function ocd_update ()
    {
        $report_id = $this->GetStateValue ("report_id", 0);
        $c_title = $this->db->GetOne ("Select title From `reports` Where report_id='$report_id'");
        $this->pageHeader = "Страницы отчета '$c_title' : режим редактирования";
        $this->pageTitle = "Страницы отчета '$c_title' : режим редактирования";

        $id = $this->GetGP ("id");
        $content = $this->enc ($this->GetValidGP ("content", "Описание", VALIDATE_NOT_EMPTY));

        $z_date = mktime (0, 0, 0, $this->GetGP ("dateMonth"), $this->GetGP ("dateDay"), $this->GetGP ("dateYear"));
        $gallery_id = $this->GetGP ("gallery_id", 0);
        if ($this->errors['err_count'] > 0) {
            $this->fill_form ("update", FORM_FROM_GP);
        }
        else
        {
            if (array_key_exists ("photo", $_FILES) and $_FILES['photo']['error'] < 3)
            {
                $symbs = getUnID (5);
                $oldname = $_FILES['photo']['name'];
                $tmp_name = $_FILES['photo']['tmp_name'];
                $short_name = $report_id."_".$symbs;
                $new_name = $short_name.".jpg";
                $thumb_name = "small_".$new_name;
                if (is_uploaded_file ($tmp_name))
                {
                    $physical_path = $this->sitePath;
                    move_uploaded_file ($tmp_name, $physical_path."data/report_photos/".$new_name);
                    $cmd = "chmod 666 ".$physical_path."data/report_photos/".$new_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/report_photos/".$new_name, 0777);
                    copy ($physical_path."data/report_photos/".$new_name, $physical_path."data/report_photos/".$thumb_name);
                    $cmd = "chmod 666 ".$physical_path."data/report_photos/".$thumb_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/report_photos/".$thumb_name, 0777);
                    makeThumbnail ($physical_path."data/report_photos/".$thumb_name, 0);
                    makeThumbnail ($physical_path."data/report_photos/".$new_name, 1);
                    $this->db->ExecuteSql ("Update `{$this->object}` Set photo='$new_name' Where file_id='$id'");
                }
            }
            $this->db->ExecuteSql ("Update `{$this->object}` Set z_date='$z_date', report_id='$report_id', content='$content' Where file_id='$id'");
            $this->Redirect ($this->pageUrl);
        }
    }

    //--------------------------------------------------------------------------
    function ocd_activate ()
    {
        $id = $this->GetGP ("id", 0);
        $this->db->ExecuteSql ("Update {$this->object} Set is_active=(1-is_active) Where file_id='$id'");
        $this->Redirect ($this->pageUrl);
    }

    //--------------------------------------------------------------------------
    function ocd_delphoto ()
    {
        $id = $this->GetGP ("id", 0);
        $filename = $this->db->GetOne ("Select photo From `{$this->object}` Where file_id='$id'");
        $physical_path = $this->sitePath;
        if (($filename!= "") and (file_exists ($physical_path."data/report_photos/".$filename))) unlink ($physical_path."data/report_photos/".$filename);
        if (($filename!= "") and (file_exists ($physical_path."data/report_photos/small_".$filename))) unlink ($physical_path."data/report_photos/small_".$filename);
        $this->db->ExecuteSql ("Update `{$this->object}` Set photo='' Where file_id='$id'");
        $this->Redirect ($this->pageUrl."?ocd=edit&id=$id");
    }

    //--------------------------------------------------------------------------
    function ocd_del ()
    {
        $id = $this->GetGP ("id", 0);
        $filename = $this->db->GetOne ("Select photo From `{$this->object}` Where file_id='$id'");
        $physical_path = $this->sitePath;
        if (($filename!= "") and (file_exists ($physical_path."data/report_photos/".$filename))) unlink ($physical_path."data/report_photos/".$filename);
        if (($filename!= "") and (file_exists ($physical_path."data/report_photos/small_".$filename))) unlink ($physical_path."data/report_photos/small_".$filename);
        $this->db->ExecuteSql ("Delete From `{$this->object}` Where file_id='$id'");
        $this->Redirect ($this->pageUrl);
    }
}

//------------------------------------------------------------------------------

$zPage = new ZPage ("report_files");

$zPage->Render ();

?>