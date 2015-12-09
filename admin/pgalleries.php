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
        $this->orderDefault = "gallery_id";
        XPage::XPage ($object);
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $this->mainTemplate = "./templates/pgalleries.tpl";
        $this->pageTitle = "Фотогалереи";
        $this->pageHeader = "Фотогалереи";

        $mes = $this->GetGP ("mes", "");
        $message = "";
        if ($mes == "ins") $message = "Фотогалерея добавлена";
        if ($mes == "up") $message = "Фотогалерея обновлена";

        $total = $this->db->GetOne ("Select Count(*) From `{$this->object}`", 0);
        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_MESSAGE" => $message,
            "HEAD_AMOUNT" => "Кол-во фотографий, акт./неакт.",
            "MAIN_ADDLINK" => "<a href='{$this->pageUrl}?ocd=new' title='Добавить галерею'><img src='./images/add.gif' /></a>",
            "HEAD_TITLE" => $this->Header_GetSortLink ("title", "Галерея"),
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
                $id = $row['gallery_id'];
                $title = $this->dec ($row['title']);
                
                $description = nl2br ($this->dec ($row['description']));
                
                
                $date = date ("d-m-Y", $row['z_date']);
                
                $amount = $this->db->GetOne ("Select Count(*) From `photos` Where gallery_id='$id' And is_active=1", 0);
                $amount_o = $this->db->GetOne ("Select Count(*) From `photos` Where gallery_id='$id' And is_active=0", 0);

                $photosLink = "<a href='photos.php?id=$id'><img src='./images/photo.gif' alt='Фотографии' title='Фотографии' /></a>";
                $activeLink = "<a href='{$this->pageUrl}?ocd=activate&id=$id'><img src='./images/active".$row['is_active'].".gif' alt='Изменить статус активности' title='Изменить статус активности'></a>";
                $editLink = "<a href='{$this->pageUrl}?ocd=edit&id=$id'><img src='./images/edit.gif' alt='Редактировать' title='Редактировать' /></a>";
                $delLink = ($amount == 0 And $amount_o == 0)? "<a href='{$this->pageUrl}?ocd=del&id=$id' onclick=\"return confirm ('Вы действительно хотите удалить эту галерею?');\"><img src='./images/garbage.gif' alt='Удалить' title='Удалить' /></a>" : "<img src='./images/garbage.gif' alt='Эта галерея содержит фотографии' />";

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
        $this->mainTemplate = "./templates/pgallery_details.tpl";
        $this->javaScripts = $this->GetJavaScript ();
        $id = $this->GetGP ("id");
        switch ($source)
        {
            case FORM_FROM_DB:

                $row = $this->db->GetEntry ("Select * From {$this->object} Where gallery_id='$id'", $this->pageUrl);
                $title = "<input type='text' name='title' value='".$row["title"]."' maxlength='120' style='width: 520px;'>";
                $description = "<textarea name='description' rows='6' style='width: 520px;'>".$row["description"]."</textarea>";
                $pdescription = "<textarea name='pdescription' id='pdescription' rows='6' style='width: 520px;'>".$row["pdescription"]."</textarea>";
                $date = getDaySelect (date ("d", $row["z_date"]), "dateDay") . getMonthSelect (date ("m", $row["z_date"]), "dateMonth") . getYearSelect (date ("Y", $row["z_date"]), "dateYear");

                break;

            case FORM_FROM_GP:

                $title = "<input type='text' name='title' value='".$this->GetGP ("title")."' maxlength='120' style='width: 520px;'>";
                $description = "<textarea name='description' rows='6' style='width: 520px;'>".$this->GetGP ("description")."</textarea>";
                $pdescription = "<textarea name='pdescription' id='pdescription' rows='6' style='width: 520px;'>".$this->GetGP ("pdescription")."</textarea>";
                $date = getDaySelect ($this->GetGP ("dateDay"), "dateDay") . getMonthSelect ($this->GetGP ("dateMonth"), "dateMonth") . getYearSelect ($this->GetGP ("dateYear"), "dateYear");

                break;

            case FORM_EMPTY:
            default:

                $title = "<input type='text' name='title' value='' maxlength='120' style='width: 520px;'>";
                $description = "<textarea name='description' rows='6' style='width: 520px;'></textarea>";
                $pdescription = "<textarea name='pdescription' id='pdescription' rows='6' style='width: 520px;'></textarea>";
                $date = getDaySelect ("", "dateDay") ." ". getMonthSelect ("", "dateMonth") ." ". getYearSelect ("", "dateYear");

                break;
        }

        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_ACTION" => $this->pageUrl,
            "MAIN_TITLE" => $title,
            "MAIN_TITLE_ERROR" => $this->GetError ("title"),
            
            "MAIN_DESCRIPTION" => $description,
            "MAIN_PDESCRIPTION" => $pdescription,
            
            "MAIN_DATE" => $date,

            "MAIN_CANCEL_URL" => $this->pageUrl,
            "MAIN_OCD" => $opCode,
            "MAIN_ID" => $id,
        );
    }

    //--------------------------------------------------------------------------
    function ocd_new ()
    {
        $this->pageTitle = "Фотогалереи: режим добавления";
        $this->pageHeader = "Фотогалереи: режим добавления";
        $this->fill_form ("insert", FORM_EMPTY);
    }

    //--------------------------------------------------------------------------
    function ocd_insert ()
    {
        $this->pageTitle = "Фотогалереи: режим добавления";
        $this->pageHeader = "Фотогалереи: режим добавления";
        $title = $this->enc ($this->GetValidGP ("title", "Название", VALIDATE_NOT_EMPTY));
        $description = $this->enc ($this->GetGP ("description", ""));
        $pdescription = $this->enc ($this->GetGP ("pdescription", ""));
        $date = mktime (0, 0, 0, $this->GetGP ("dateMonth"), $this->GetGP ("dateDay"), $this->GetGP ("dateYear"));

        if ($this->errors['err_count'] > 0) {
            $this->fill_form ("insert", FORM_FROM_GP);
        }
        else
        {
            $this->db->ExecuteSql ("Insert into {$this->object} (`title`, `description`, `pdescription`, `z_date`, `is_active`) values ('$title', '$description', '$pdescription', '$date', 0)");
            $this->Redirect ($this->pageUrl."?mes=ins");
        }
    }

    //--------------------------------------------------------------------------
    function ocd_edit ()
    {
        $this->pageTitle = "Фотогалереи: режим редактирования";
        $this->pageTitle = "Фотогалереи: режим редактирования";
        $this->fill_form ("update", FORM_FROM_DB);
    }

    //--------------------------------------------------------------------------
    function ocd_update ()
    {
        $this->pageTitle = "Фотогалереи: режим редактирования";
        $this->pageTitle = "Фотогалереи: режим редактирования";
        $id = $this->GetGP ("id");

        $title = $this->enc ($this->GetValidGP ("title", "Название", VALIDATE_NOT_EMPTY));
        $description = $this->enc ($this->GetGP ("description", ""));
        $pdescription = $this->enc ($this->GetGP ("pdescription", ""));
        $date = mktime (0, 0, 0, $this->GetGP ("dateMonth"), $this->GetGP ("dateDay"), $this->GetGP ("dateYear"));
        
        if ($this->errors['err_count'] > 0)
        {
            $this->fill_form ("update", FORM_FROM_GP);
        }
        else
        {
            $this->db->ExecuteSQL ("Update {$this->object} Set `title`='$title', `description`='$description', `pdescription`='$pdescription', `z_date`='$date' Where gallery_id='$id'");
            $this->Redirect ($this->pageUrl."?mes=up");
        }
    }

    //--------------------------------------------------------------------------
    function ocd_activate ()
    {
        $id = $this->GetGP ("id", 0);
        $this->db->ExecuteSql ("Update {$this->object} Set is_active=(1-is_active) Where gallery_id='$id'");
        $this->Redirect ($this->pageUrl);
    }

    //--------------------------------------------------------------------------
    function ocd_del ()
    {
        $id = $this->GetGP ("id", 0);
        $this->db->ExecuteSql ("Delete From {$this->object} Where gallery_id='$id'");
        $this->Redirect ($this->pageUrl);
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

$zPage = new ZPage ("pgalleries");
$zPage->Render ();

?>