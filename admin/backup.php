<?php
require_once ("../includes/config.php");
require_once ("../includes/xtemplate.php");
require_once ("../includes/xpage_admin.php");
require_once ("../includes/dumper.php");
require_once ("../includes/utilities.php");

class ZPage extends XPage
{

    //--------------------------------------------------------------------------
    function ZPage ($object)
    {
        XPage::XPage ($object);
        $this->mainTemplate = "./templates/backup.tpl";
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $this->pageTitle = "Резервные копии DB";
        $this->pageHeader = "Резервные копии DB";
        $ec = $this->GetGP ("ec", "");
        $fn = $this->GetGP ("fn", "");
        $main_message = "";

        if ($ec == "restore_error") $main_message = "<span class='error'>Ошибка: Файл на выбран.</span>";
        if ($ec == "restore_ok") $main_message = "<span class='message'>DB была успешно восстановлена из файла '$fn'.</span>";
        if ($ec == "backup_ok") $main_message = "<span class='message'>Копия DB была успешно создана. Файл - '$fn'.</span>";
        if ($ec == "delete_error") $main_message = "<span class='error'>Ошибка: Файл на выбран.</span>";
        if ($ec == "delete_ok") $main_message = "<span class='message'>Файл '$fn' был успешно удален.</span>";

        $total = $this->db->GetOne ("Select Count(*) From {$this->object}");
        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_MESSAGE" => $main_message,
            "MAIN_ACTION" => $this->pageUrl,
            "MAIN_OCD" => "reset",
            "MAIN_ADDLINK" => "<a href='{$this->pageUrl}?ocd=backup' title='Создать резервную копию' onClick=\"return confirm ('Создать новую копию базы данных?');\"><img src='./images/add.gif' border='0'></a>",
            "HEAD_TITLE" => "<b>Имя_Дата_Время создания</b>",
        );

        $appRoot = $this->sitePath;
        $urlRoot = $this->siteUrl;

        $dumper = new Dumper (DbName, $appRoot."data/backups/");

        $files = array ();
        $files = $dumper->file_select ();

        array_shift ($files);
        $total = count ($files);

        $bgcolor = "";
        if ($total > 0)
        {
            foreach ($files as $file=>$title)
            {

                $recoverLink = "<a href='{$this->pageUrl}?ocd=restore&file=$file' onClick=\"return confirm ('Вы действительно хотите восстановить DB? Текущая DB будет удалена!');\"><img src='./images/infinity1.gif' width='22' border='0' alt='Восстановить базу данных из этого файла' title='Восстановить базу данных из этого файла'></a>";
                $delLink = "<a href='{$this->pageUrl}?ocd=delete&file=$file' onClick=\"return confirm ('Вы действительно хотите удалить эту копию?');\"><img src='./images/garbage.gif' width='13' border='0' alt='Удалить' title='Удалить'></a>";
                $downLink = "<a href='".$urlRoot."data/backups/".$file."'><img src='./images/down.gif' width='16' border='0' alt='Скачать этот файл' title='Скачать этот файл'></a>";

                $bgcolor = ($bgcolor == "") ? "#E7E7E7" : "";
                $this->data ['TABLE_ROW'][] = array (
                    "ROW_TITLE" => $title,
                    "ROW_RECOVERLINK" => $recoverLink,
                    "ROW_DOWNLINK" => $downLink,
                    "ROW_DELLINK" => $delLink,
                    "ROW_BGCOLOR" => $bgcolor
                );
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
    function ocd_backup ()
    {
        $appRoot = $this->sitePath;
        $dumper = new Dumper (DbName, $appRoot."data/backups/");
        $file_name = $dumper->backup ();
        $this->Redirect ($this->pageUrl."?ec=backup_ok&fn=$file_name");

    }

    //--------------------------------------------------------------------------
    function ocd_restore ()
    {
        $file = $this->GetGP ("file");

        $appRoot = $this->sitePath;
        $dumper = new Dumper (DbName, $appRoot."data/backups/");

        $file_name = $dumper->restore ($file);
        if ($file_name != "") {
            $this->Redirect ($this->pageUrl."?ec=restore_ok&fn=$file_name");
        }
        else
        {
            $this->Redirect ($this->pageUrl."?ec=restore_error");
        }
    }

    //--------------------------------------------------------------------------
    function ocd_delete ()
    {
        $file = $this->GetGP ("file");

        $physical_path = $this->sitePath;
        if ($file != "" and file_exists ($physical_path."data/backups/".$file))
        {
            unlink ($physical_path."data/backups/".$file);
            $this->Redirect ($this->pageUrl."?ec=delete_ok&fn=$file");
        }
        else
        {
            $this->Redirect ($this->pageUrl."?ec=delete_error");
        }
    }
}

//------------------------------------------------------------------------------

$zPage = new ZPage ("backup");

$zPage->Render ();

?>