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
        $this->mainTemplate = "./templates/news.tpl";
        $this->pageTitle = "Новости";
        $this->pageHeader = "Новости";
        
        $physical_path = $this->sitePath;
        
        $mes = $this->GetGP ("mes", "");
        $message = "";
        if ($mes == "ins") $message = "Спасибо. Новость успешно добавлена";
        if ($mes == "up") $message = "Спасибо. Новость успешно обновлена";

        $total = $this->db->GetOne ("Select Count(*) From {$this->object}", 0);
        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_MESSAGE" => $message,
            "MAIN_ADDLINK" => "<a href='{$this->pageUrl}?ocd=new' title='Добавить новость'><img src='./images/add.gif' border='0'></a>",
            "HEAD_DATE" => "Дата",
            "HEAD_TITLE" => "Заголовок",
            "HEAD_DESCRIPTION" => "Краткое описание",
            "MAIN_PAGES" => $this->Pages_GetLinks ($total, $this->pageUrl."?"),
        );

        $bgcolor = "";
        if ($total > 0)
        {
            $result = $this->db->ExecuteSql ("Select * From {$this->object} Order By z_date Desc", true);
            while ($row = $this->db->FetchInArray ($result))
            {
                $id = $row['item_id'];
                $title = $this->dec ($row['title']);
                $description = nl2br ($this->dec ($row['article']));
                
                $date = date ("d-m-Y", $row['z_date']);

                $activeLink = "<a href='{$this->pageUrl}?ocd=activate&id=$id'><img src='./images/active".$row['is_active'].".gif' width='14' border='0' alt='Изменить статус активности' title='Изменить статус активности'></a>";
                $editLink = "<a href='{$this->pageUrl}?ocd=edit&id=$id'><img src='./images/edit.gif' width='15' border='0' alt='Редактировать новость' title='Редактировать новость'></a>";
                $delLink = "<a href='{$this->pageUrl}?ocd=del&id=$id' onClick=\"return confirm ('Вы действительно хотите удалить эту новость?');\"><img src='./images/garbage.gif' width='13' border='0' alt='Удалить новость' title='Удалить новость'></a>";
                $bgcolor = ($bgcolor == "") ? "#E7E7E7" : "";
                $this->data ['TABLE_ROW'][] = array (
                    "ROW_TITLE" => $title,
                    "ROW_DESCRIPTION" => $description,
                    
                    "ROW_DATE" => $date,

                    "ROW_ACTIVELINK" => $activeLink,
                    "ROW_EDITLINK" => $editLink,
                    "ROW_DELLINK" => $delLink,
                    "ROW_BGCOLOR" => $bgcolor
                );
            }
            $this->db->FreeSqlResult ($result);
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
        $this->mainTemplate = "./templates/news_details.tpl";
        $this->javaScripts = $this->GetJavaScript ();
        $id = $this->GetGP ("id");
        switch ($source)
        {
            case FORM_FROM_DB:
                $row = $this->db->GetEntry ("Select * From {$this->object} Where item_id='$id'", $this->pageUrl);
                $date = getDaySelect (date ("d", $row["z_date"]), "dateDay") . getMonthSelect (date ("m", $row["z_date"]), "dateMonth") . getYearSelect (date ("Y", $row["z_date"]), "dateYear");
                $article = "<textarea name='article' rows='6' style='width: 520px;'>".$row["article"]."</textarea>";
                $title = "<input type='text' name='title' value='".$row["title"]."' maxlength='150' style='width: 520px;'>";                
                
                $description = $row["description"];
                $photo = "";
                if ($row["photo"] != "" And file_exists ("../data/news/".$row["photo"].".jpg"))
                {
                    $photo = "<a class='menu' href='../data/news/".$row["photo"].".jpg' target='_blank'><img title='Увеличить' border='0' src='../data/news/small_".$row["photo"].".jpg' class='img_w_border'></a>";
                    $photo .= "&nbsp;&nbsp;<a class='menu' href='{$this->pageUrl}?ocd=delphoto&id=$id' onClick=\"return confirm ('Вы действительно хотите удалить фото?')\"><img src='./images/garbage.gif' alt='Удалить фото 1' title='Удалить фото 1' /></a> <br /><br />";
                }
                else
                {
                    $photo .= "<input type='file' name='photo' value='' style='width: 320px;'>";
                }

                $photo2 = "";
                if ($row["photo2"] != "" And file_exists ("../data/news/".$row["photo2"].".jpg"))
                {
                    $photo2 = "<a class='menu' href='../data/news/".$row["photo2"].".jpg' target='_blank'><img title='Увеличить' border='0' src='../data/news/small_".$row["photo2"].".jpg' class='img_w_border'></a>";
                    $photo2 .= "&nbsp;&nbsp;<a class='menu' href='{$this->pageUrl}?ocd=delphoto2&id=$id' onClick=\"return confirm ('Вы действительно хотите удалить фото?')\"><img src='./images/garbage.gif' alt='Удалить фото 2' title='Удалить фото 2' /></a> <br /><br />";
                }
                else
                {
                    $photo2 .= "<input type='file' name='photo2' value='' style='width: 320px;'>";
                }

                break;

            case FORM_FROM_GP:

                $date = getDaySelect ($this->GetGP ("dateDay"), "dateDay") . getMonthSelect ($this->GetGP ("dateMonth"), "dateMonth") . getYearSelect ($this->GetGP ("dateYear"), "dateYear");
                $article = "<textarea name='article' rows='6' style='width: 520px;'>".$this->GetGP ("article")."</textarea>";
                $title = "<input type='text' name='title' value='".$this->GetGP ("title")."' maxlength='150' style='width: 520px;'>";
                $description = $this->GetGP ("content");
                $photo = "";
                $photo_file = $this->db->GetOne ("Select photo From {$this->object} Where item_id='$id'");
                if ($photo_file != "" And file_exists ("../data/news/".$photo_file) And $opCode == "update")
                {
                    $photo = "<a class='menu' href='../data/news/".$photo_file.".jpg' target='_blank'><img title='Увеличить' border='0' src='../data/news/small_".$photo_file.".jpg' class='img_w_border'></a>";
                    $photo .= "&nbsp;&nbsp;<a class='menu' href='{$this->pageUrl}?ocd=delphoto&id=$id'  onClick=\"return confirm ('Вы действительно хотите удалить фото?')\"><img src='./images/garbage.gif' alt='Удалить фото 1' title='Удалить фото 1' /></a> <br /><br />";
                }
                else
                {
                    $photo .= "<input type='file' name='photo' value='' style='width: 320px;'>";
                }

                $photo2 = "";
                $photo_file2 = $this->db->GetOne ("Select photo2 From {$this->object} Where item_id='$id'");
                if ($photo_file2 != "" And file_exists ("../data/news/".$photo_file2) And $opCode == "update")
                {
                    $photo2 = "<a class='menu' href='../data/news/".$photo_file2.".jpg' target='_blank'><img title='Увеличить' border='0' src='../data/news/small_".$photo_file2.".jpg' class='img_w_border'></a>";
                    $photo2 .= "&nbsp;&nbsp;<a class='menu' href='{$this->pageUrl}?ocd=delphoto2&id=$id'  onClick=\"return confirm ('Вы действительно хотите удалить фото?')\"><img src='./images/garbage.gif' alt='Удалить фото 2' title='Удалить фото 2' /></a> <br /><br />";
                }
                else
                {
                    $photo2 .= "<input type='file' name='photo2' value='' style='width: 320px;'>";
                }

                break;

            case FORM_EMPTY:
            default:

                $date = getDaySelect ("", "dateDay") ." ". getMonthSelect ("", "dateMonth") ." ". getYearSelect ("", "dateYear");
                $title = "<input type='text' name='title' value='' maxlength='150' style='width: 520px;'>";
                $article = "<textarea name='article' rows='6' style='width: 520px;' class='many_lines'></textarea>";
                $description = "";
                $photo = "<input type='file' name='photo' value='' style='width: 320px;' class='one_line'>";
                $photo2 = "<input type='file' name='photo2' value='' style='width: 320px;' class='one_line'>";

                break;
        }

        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_ACTION" => $this->pageUrl,
            "MAIN_DATE" => $date,
            
            "MAIN_ARTICLE" => $article,
            "MAIN_ARTICLE_ERROR" => $this->GetError ("article"),
            
            "MAIN_TITLE" => $title,
            "MAIN_TITLE_ERROR" => $this->GetError ("title"),
            
            "MAIN_DESCRIPTION" => $this->dec ($description),
            "MAIN_PHOTO" => $photo,
            "MAIN_PHOTO2" => $photo2,

            "MAIN_CONTENT" => $description,
            "MAIN_CANCEL_URL" => $this->pageUrl,
            "MAIN_OCD" => $opCode,
            "MAIN_ID" => $id,
        );
    }

    //--------------------------------------------------------------------------
    function ocd_new ()
    {
        $this->pageTitle = "Новости : режим добавления";
        $this->pageHeader = "Новости : режим добавления";
        $this->fill_form ("insert", FORM_EMPTY);
    }

    //--------------------------------------------------------------------------
    function ocd_insert ()
    {
        $this->pageTitle = "Новости : режим добавления";
        $this->pageHeader = "Новости : режим добавления";

        $article = $this->enc ($this->GetValidGP ("article", "Краткое описание", VALIDATE_NOT_EMPTY));
        $title = $this->enc ($this->GetValidGP ("title", "Заголовок", VALIDATE_NOT_EMPTY));
        
        if ($this->errors['err_count'] > 0) {
            $this->fill_form ("insert", FORM_FROM_GP);
        }
        else
        {
            $date = mktime (0, 0, 0, $this->GetGP ("dateMonth"), $this->GetGP ("dateDay"), $this->GetGP ("dateYear"));
            $description = $this->enc ($this->GetGP ("progress"));
            $this->db->ExecuteSql ("Insert into {$this->object} (z_date, title, article, description, is_active) values ('$date', '$title', '$article', '$description', '1')");

            $physical_path = $this->sitePath;
            
            $id = $this->db->GetInsertID ();
            if (array_key_exists ("photo", $_FILES) and $_FILES['photo']['error'] < 3)
            {
                $symbs = getUnID (5);
                $oldname = $_FILES['photo']['name'];
                $tmp_name = $_FILES['photo']['tmp_name'];
                $short_name = $id."_".$symbs;
                $new_name = $short_name.".jpg";
                $thumb_name = "small_".$new_name;
                if (is_uploaded_file ($tmp_name))
                {
                    move_uploaded_file ($tmp_name, $physical_path."data/news/".$new_name);
                    $cmd = "chmod 666 ".$physical_path."data/news/".$new_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/news/".$new_name, 0777);
                    copy ($physical_path."data/news/".$new_name, $physical_path."data/news/".$thumb_name);
                    $cmd = "chmod 666 ".$physical_path."data/news/".$thumb_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/news/".$thumb_name, 0777);
                    makeThumbnail ($physical_path."data/news/".$thumb_name, 0);
                    makeThumbnail ($physical_path."data/news/".$new_name, 1);
                    $this->db->ExecuteSql ("Update {$this->object} Set photo='$short_name' Where item_id='$id'");
                }
            }

            if (array_key_exists ("photo2", $_FILES) and $_FILES['photo2']['error'] < 3)
            {
                $symbs = getUnID (5);
                $oldname = $_FILES['photo2']['name'];
                $tmp_name = $_FILES['photo2']['tmp_name'];
                $short_name = $id."_".$symbs;
                $new_name = $short_name.".jpg";
                $thumb_name = "small_".$new_name;
                if (is_uploaded_file ($tmp_name))
                {
                    move_uploaded_file ($tmp_name, $physical_path."data/news/".$new_name);
                    $cmd = "chmod 666 ".$physical_path."data/news/".$new_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/news/".$new_name, 0777);
                    copy ($physical_path."data/news/".$new_name, $physical_path."data/news/".$thumb_name);
                    $cmd = "chmod 666 ".$physical_path."data/news/".$thumb_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/news/".$thumb_name, 0777);
                    makeThumbnail ($physical_path."data/news/".$thumb_name, 0);
                    makeThumbnail ($physical_path."data/news/".$new_name, 1);
                    $this->db->ExecuteSql ("Update {$this->object} Set photo2='$short_name' Where item_id='$id'");
                }
            }
            $this->Redirect ($this->pageUrl."?mes=ins");
        }
    }

    //--------------------------------------------------------------------------
    function ocd_edit ()
    {
        $this->pageTitle = "Новости : режим редактирования";
        $this->pageHeader = "Новости : режим редактирования";
        $this->fill_form ("update", FORM_FROM_DB);
    }

    //--------------------------------------------------------------------------
    function ocd_update ()
    {
        $this->pageTitle = "Новости : режим редактирования";
        $this->pageHeader = "Новости : режим редактирования";
        $id = $this->GetGP ("id");
        $article = $this->enc ($this->GetValidGP ("article", "Краткое описание", VALIDATE_NOT_EMPTY));
        $title = $this->enc ($this->GetValidGP ("title", "Заголовок", VALIDATE_NOT_EMPTY));
        
        if ($this->errors['err_count'] > 0) {
            $this->fill_form ("update", FORM_FROM_GP);
        }
        else
        {
            $date = mktime (0, 0, 0, $this->GetGP ("dateMonth"), $this->GetGP ("dateDay"), $this->GetGP ("dateYear"));
            $description = $this->enc ($this->GetGP ("progress"));
            $this->db->ExecuteSql ("Update {$this->object} Set z_date='$date', article='$article', title='$title', description='$description' Where item_id='$id'");
            $physical_path = $this->sitePath;
           if (array_key_exists ("photo", $_FILES) and $_FILES['photo']['error'] < 3)
            {
                $symbs = getUnID (5);
                $oldname = $_FILES['photo']['name'];
                $tmp_name = $_FILES['photo']['tmp_name'];
                $short_name = $id."_".$symbs;
                $new_name = $short_name.".jpg";
                $thumb_name = "small_".$new_name;
                if (is_uploaded_file ($tmp_name))
                {
                    move_uploaded_file ($tmp_name, $physical_path."data/news/".$new_name);
                    $cmd = "chmod 666 ".$physical_path."data/news/".$new_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/news/".$new_name, 0777);
                    copy ($physical_path."data/news/".$new_name, $physical_path."data/news/".$thumb_name);
                    $cmd = "chmod 666 ".$physical_path."data/news/".$thumb_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/news/".$thumb_name, 0777);
                    makeThumbnail ($physical_path."data/news/".$thumb_name, 0);
                    makeThumbnail ($physical_path."data/news/".$new_name, 1);
                    $this->db->ExecuteSql ("Update {$this->object} Set photo='$short_name' Where item_id='$id'");
                }
            }

            if (array_key_exists ("photo2", $_FILES) and $_FILES['photo2']['error'] < 3)
            {
                $symbs = getUnID (5);
                $oldname = $_FILES['photo2']['name'];
                $tmp_name = $_FILES['photo2']['tmp_name'];
                $short_name = $id."_".$symbs;
                $new_name = $short_name.".jpg";
                $thumb_name = "small_".$new_name;
                if (is_uploaded_file ($tmp_name))
                {
                    move_uploaded_file ($tmp_name, $physical_path."data/news/".$new_name);
                    $cmd = "chmod 666 ".$physical_path."data/news/".$new_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/news/".$new_name, 0777);
                    copy ($physical_path."data/news/".$new_name, $physical_path."data/news/".$thumb_name);
                    $cmd = "chmod 666 ".$physical_path."data/news/".$thumb_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/news/".$thumb_name, 0777);
                    makeThumbnail ($physical_path."data/news/".$thumb_name, 0);
                    makeThumbnail ($physical_path."data/news/".$new_name, 1);
                    $this->db->ExecuteSql ("Update {$this->object} Set photo2='$short_name' Where item_id='$id'");
                }
            }
            $this->Redirect ($this->pageUrl."?mes=up");
        }
    }

    //--------------------------------------------------------------------------
    function ocd_activate ()
    {
        $id = $this->GetGP ("id", 0);
        $this->db->ExecuteSql ("Update {$this->object} Set is_active=(1-is_active) Where item_id=$id");
        $this->Redirect ($this->pageUrl);
    }

    //--------------------------------------------------------------------------
    function ocd_del ()
    {
        $id = $this->GetGP ("id", 0);
        $filename = $this->db->GetOne ("Select photo From {$this->object} Where item_id='$id'");

        $filename2 = $this->db->GetOne ("Select photo2 From {$this->object} Where item_id='$id'");

        $physical_path = $this->sitePath;
        if (($filename != "") and (file_exists ($physical_path."data/news/".$filename.".jpg"))) unlink ($physical_path."data/news/".$filename.".jpg");
        if (($filename != "") and (file_exists ($physical_path."data/news/small_".$filename.".jpg"))) unlink ($physical_path."data/news/small_".$filename.".jpg");

        if (($filename2 != "") and (file_exists ($physical_path."data/news/".$filename2.".jpg"))) unlink ($physical_path."data/news/".$filename2.".jpg");
        if (($filename2 != "") and (file_exists ($physical_path."data/news/small_".$filename2.".jpg"))) unlink ($physical_path."data/news/small_".$filename2.".jpg");

        $this->db->ExecuteSql ("Delete From {$this->object} Where item_id='$id'");
        $this->Redirect ($this->pageUrl);
    }

    //--------------------------------------------------------------------------
    function ocd_delphoto ()
    {
        $id = $this->GetGP ("id", 0);
        $filename = $this->db->GetOne ("Select photo From {$this->object} Where item_id='$id'");
        $physical_path = $this->sitePath;
        if (($filename != "") and (file_exists ($physical_path."data/news/".$filename.".jpg"))) unlink ($physical_path."data/news/".$filename.".jpg");
        if (($filename != "") and (file_exists ($physical_path."data/news/small_".$filename.".jpg"))) unlink ($physical_path."data/news/small_".$filename.".jpg");
        $this->db->ExecuteSql ("Update {$this->object} Set photo='' Where item_id='$id'");
        $this->Redirect ($this->pageUrl."?ocd=edit&id=$id");
    }

    //--------------------------------------------------------------------------
    function ocd_delphoto2 ()
    {
        $id = $this->GetGP ("id", 0);
        $filename = $this->db->GetOne ("Select photo2 From {$this->object} Where item_id='$id'");
        $physical_path = $this->sitePath;
        if (($filename != "") and (file_exists ($physical_path."data/news/".$filename.".jpg"))) unlink ($physical_path."data/news/".$filename.".jpg");
        if (($filename != "") and (file_exists ($physical_path."data/news/small_".$filename.".jpg"))) unlink ($physical_path."data/news/small_".$filename.".jpg");
        $this->db->ExecuteSql ("Update {$this->object} Set photo2='' Where item_id='$id'");
        $this->Redirect ($this->pageUrl."?ocd=edit&id=$id");
    }
    
    //--------------------------------------------------------------------------
    function GetJavaScript ()
    {
        return <<<_ENDOFJS_
        <script language='JavaScript' src='./editor/scripts/innovaeditor.js'></script>
_ENDOFJS_;
    }

}

//------------------------------------------------------------------------------

$zPage = new ZPage ("news");

$zPage->Render ();

?>