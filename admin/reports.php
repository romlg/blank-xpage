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
        $this->orderDefault = "z_date";
        XPage::XPage ($object);
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $this->mainTemplate = "./templates/reports.tpl";
        $this->pageTitle = "Отчеты";
        $this->pageHeader = "Отчеты";

        $mes = $this->GetGP ("mes", "");
        $message = "";
        if ($mes == "ins") $message = "Отчет добавлен";
        if ($mes == "up") $message = "Отчет обновлен";

        $total = $this->db->GetOne ("Select Count(*) From `{$this->object}`", 0);
        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_MESSAGE" => $message,
            "HEAD_AMOUNT" => "Кол-во страниц, акт./неакт.",
            "MAIN_ADDLINK" => "<a href='{$this->pageUrl}?ocd=new' title='Добавить отчет'><img src='./images/add.gif' /></a>",
            "HEAD_TITLE" => $this->Header_GetSortLink ("title", "Отчет"),
            "HEAD_DATE" => $this->Header_GetSortLink ("z_date", "Дата регистрации"),
            "HEAD_DESCRIPTION" => $this->Header_GetSortLink ("description", "Описание"),
            "MAIN_PAGES" => $this->Pages_GetLinks ($total, $this->pageUrl."?"),
        );
        
        $bgcolor = "";
        if ($total > 0)
        {
            $result = $this->db->ExecuteSql ("Select * From {$this->object} Order By {$this->orderBy} {$this->orderDir}", true);
            while ($row = $this->db->FetchInArray ($result))
            {
                $id = $row['report_id'];
                $title = $this->dec ($row['title']);
                
                $description = nl2br ($this->dec ($row['description']));
                $date = date ("d-m-Y", $row['z_date']);
                
                $amount = $this->db->GetOne ("Select Count(*) From `report_files` Where report_id='$id' And is_active=1", 0);
                $amount_o = $this->db->GetOne ("Select Count(*) From `report_files` Where report_id='$id' And is_active=0", 0);

                $photosLink = "<a href='report_files.php?id=$id'><img src='./images/photo.gif' alt='Страницы' title='Страницы' /></a>";
                $activeLink = "<a href='{$this->pageUrl}?ocd=activate&id=$id'><img src='./images/active".$row['is_active'].".gif' alt='Изменить статус активности' title='Изменить статус активности'></a>";
                $editLink = "<a href='{$this->pageUrl}?ocd=edit&id=$id'><img src='./images/edit.gif' alt='Редактировать' title='Редактировать' /></a>";
                $delLink = ($amount == 0 And $amount_o == 0)? "<a href='{$this->pageUrl}?ocd=del&id=$id' onclick=\"return confirm ('Вы действительно хотите удалить этот отчет?');\"><img src='./images/garbage.gif' alt='Удалить' title='Удалить' /></a>" : "<img src='./images/garbage.gif' alt='Этот отчет содержит страницы' title='Этот отчет содержит страницы' />";

                $bgcolor = ($bgcolor == "") ? "#E7E7E7" : "";
                $this->data ['TABLE_ROW'][] = array (
                    "ROW_TITLE" => $title,
                    "ROW_DESCRIPTION" => $description,
                    "ROW_DATE" => $date,
                    "ROW_AMOUNT" => $amount." / ".$amount_o,
                    "ROW_PHOTOLINK" => $photosLink,
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
        $this->mainTemplate = "./templates/report_details.tpl";
        $id = $this->GetGP ("id");
        switch ($source)
        {
            case FORM_FROM_DB:
                $row = $this->db->GetEntry ("Select * From {$this->object} Where report_id='$id'", $this->pageUrl);
                $title = "<input type='text' name='title' value='".$row["title"]."' maxlength='120' style='width: 520px;' />";
                $description = "<textarea name='description' rows='6' style='width: 520px;'>".$row["description"]."</textarea>";
                $date = getDaySelect (date ("d", $row["z_date"]), "dateDay") . getMonthSelect (date ("m", $row["z_date"]), "dateMonth") . getYearSelect (date ("Y", $row["z_date"]), "dateYear");
                if ($row["document"] != "" And file_exists ("../data/reports/".$row["document"]))
                {
                    $document = "<a class='menu' href='../data/reports/".$row["document"]."' target='_blank'>Скачать/Открыть</a>";
                    $document .= "&nbsp;&nbsp;<a class='menu' href='{$this->pageUrl}?ocd=delfile&id=$id' onClick=\"return confirm ('Вы действительно хотите удалить документ?')\"><img src='./images/garbage.gif' alt='Удалить документ' title='Удалить документ' /></a> <br /><br />";
                }
                else
                {
                    $document = "<input type='file' name='document' value='' style='width: 320px;' />";
                }
                break;

            case FORM_FROM_GP:
                $title = "<input type='text' name='title' value='".$this->GetGP ("title")."' maxlength='120' style='width: 520px;' />";
                $description = "<textarea name='description' rows='6' style='width: 520px;'>".$this->GetGP ("description")."</textarea>";
                $date = getDaySelect ($this->GetGP ("dateDay"), "dateDay") . getMonthSelect ($this->GetGP ("dateMonth"), "dateMonth") . getYearSelect ($this->GetGP ("dateYear"), "dateYear");

                $doc_file = $this->db->GetOne ("Select document From {$this->object} Where report_id='$id'");
                if ($doc_file != "" And file_exists ("../data/reports/".$doc_file))
                {
                    $document = "<a class='menu' href='../data/reports/".$doc_file."' target='_blank'>Скачать/Открыть</a>";
                    $document .= "&nbsp;&nbsp;<a class='menu' href='{$this->pageUrl}?ocd=delfile&id=$id' onClick=\"return confirm ('Вы действительно хотите удалить документ?')\"><img src='./images/garbage.gif' alt='Удалить документ' title='Удалить документ' /></a> <br /><br />";
                }
                else
                {
                    $document = "<input type='file' name='document' value='' style='width: 320px;' />";
                }
                break;

            case FORM_EMPTY:
            default:
                $title = "<input type='text' name='title' value='' maxlength='120' style='width: 520px;' />";
                $description = "<textarea name='description' rows='6' style='width: 520px;'></textarea>";
                $date = getDaySelect ("", "dateDay") ." ". getMonthSelect ("", "dateMonth") ." ". getYearSelect ("", "dateYear");
                $document = "<input type='file' name='document' value='' style='width: 320px;' />";
                break;
        }

        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_ACTION" => $this->pageUrl,
            "MAIN_TITLE" => $title,
            "MAIN_TITLE_ERROR" => $this->GetError ("title"),
            "MAIN_DESCRIPTION" => $description,
            "MAIN_DATE" => $date,
            "MAIN_DOCUMENT" => $document,

            "MAIN_CANCEL_URL" => $this->pageUrl,
            "MAIN_OCD" => $opCode,
            "MAIN_ID" => $id,
        );
    }

    //--------------------------------------------------------------------------
    function ocd_new ()
    {
        $this->pageTitle = "Отчеты: режим добавления";
        $this->pageHeader = "Отчеты: режим добавления";
        $this->fill_form ("insert", FORM_EMPTY);
    }

    //--------------------------------------------------------------------------
    function ocd_insert ()
    {
        $this->pageTitle = "Отчеты: режим добавления";
        $this->pageHeader = "Отчеты: режим добавления";
        $title = $this->enc ($this->GetValidGP ("title", "Название", VALIDATE_NOT_EMPTY));
        $description = $this->enc ($this->GetGP ("description", ""));
        $date = mktime (0, 0, 0, $this->GetGP ("dateMonth"), $this->GetGP ("dateDay"), $this->GetGP ("dateYear"));

        if ($this->errors['err_count'] > 0) {
            $this->fill_form ("insert", FORM_FROM_GP);
        }
        else
        {
            $this->db->ExecuteSql ("Insert into {$this->object} (`title`, `description`, `z_date`, `is_active`) values ('$title', '$description', '$date', 0)");
            $id = $this->db->GetInsertID ();

            if (array_key_exists ("document", $_FILES) and $_FILES['document']['error'] < 3)
            {
                $physical_path = $this->sitePath;
                $new_name = $_FILES['document']['name'];
                $tmp_name = $_FILES['document']['tmp_name'];
                if (is_uploaded_file ($tmp_name))
                {
                    move_uploaded_file ($tmp_name, $physical_path."data/reports/".$new_name);
                    $cmd = "chmod 666 ".$physical_path."data/reports/".$new_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/reports/".$new_name, 0777);
                    $this->db->ExecuteSql ("Update {$this->object} Set document='$new_name' Where report_id='$id'");
                }
            }

            $this->Redirect ($this->pageUrl."?mes=ins");
        }
    }

    //--------------------------------------------------------------------------
    function ocd_edit ()
    {
        $this->pageTitle = "Отчеты: режим редактирования";
        $this->pageTitle = "Отчеты: режим редактирования";
        $this->fill_form ("update", FORM_FROM_DB);
    }

    //--------------------------------------------------------------------------
    function ocd_update ()
    {
        $this->pageTitle = "Отчеты: режим редактирования";
        $this->pageTitle = "Отчеты: режим редактирования";
        $id = $this->GetGP ("id");

        $title = $this->enc ($this->GetValidGP ("title", "Название", VALIDATE_NOT_EMPTY));
        $description = $this->enc ($this->GetGP ("description", ""));
        $date = mktime (0, 0, 0, $this->GetGP ("dateMonth"), $this->GetGP ("dateDay"), $this->GetGP ("dateYear"));
        
        if ($this->errors['err_count'] > 0)
        {
            $this->fill_form ("update", FORM_FROM_GP);
        }
        else
        {
            $this->db->ExecuteSql ("Update {$this->object} Set `title`='$title', `description`='$description', `z_date`='$date' Where report_id='$id'");

            if (array_key_exists ("document", $_FILES) and $_FILES['document']['error'] < 3)
            {
                $new_name = $_FILES['document']['name'];
                $tmp_name = $_FILES['document']['tmp_name'];
                if (is_uploaded_file ($tmp_name))
                {
                    $physical_path = $this->sitePath;
                    move_uploaded_file ($tmp_name, $physical_path."data/reports/".$new_name);
                    $cmd = "chmod 666 ".$physical_path."data/reports/".$new_name;
                    @exec ($cmd, $output, $retval);
                    @chmod ($physical_path."data/reports/".$new_name, 0777);
                    $this->db->ExecuteSql ("Update {$this->object} Set document='$new_name' Where report_id='$id'");
                }
            }

            $this->Redirect ($this->pageUrl."?mes=up");
        }
    }

    //--------------------------------------------------------------------------
    function ocd_activate ()
    {
        $id = $this->GetGP ("id", 0);
        $this->db->ExecuteSql ("Update {$this->object} Set is_active=(1-is_active) Where report_id='$id'");
        $this->Redirect ($this->pageUrl);
    }

    //--------------------------------------------------------------------------
    function ocd_delfile ()
    {
        $id = $this->GetGP ("id", 0);
        $filename = $this->db->GetOne ("Select document From {$this->object} Where report_id='$id'");
        $physical_path = $this->sitePath;
        if (($filename != "") and (file_exists ($physical_path."data/reports/".$filename))) unlink ($physical_path."data/reports/".$filename);
        $this->db->ExecuteSql ("Update {$this->object} Set document='' Where report_id='$id'");
        $this->Redirect ($this->pageUrl."?ocd=edit&id=$id");
    }

    //--------------------------------------------------------------------------
    function ocd_del ()
    {
        $id = $this->GetGP ("id", 0);
        
        $filename = $this->db->GetOne ("Select document From {$this->object} Where report_id='$id'");
        $physical_path = $this->sitePath;
        if (($filename != "") and (file_exists ($physical_path."data/reports/".$filename))) unlink ($physical_path."data/reports/".$filename);

        $this->db->ExecuteSql ("Delete From {$this->object} Where report_id='$id'");
        $this->Redirect ($this->pageUrl);
    }
    
}

//------------------------------------------------------------------------------

$zPage = new ZPage ("reports");
$zPage->Render ();

?>