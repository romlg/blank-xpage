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
        XPage::XPage ($object);
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $this->mainTemplate = "./templates/s_pages.tpl";

        $destination = $this->GetGP ("id", 0);
        if ($destination > 0)
        {
            $this->SaveStateValue ("destination", $destination);
        }
        $destination = $this->GetStateValue ("destination", 0);

        $name = $this->dec ($this->db->GetOne ("Select title From `{$this->object}` Where page_id='$destination' And destination=0", "no"));
        if ($name == "no") $this->Redirect ("pages.php");
        $this->pageTitle = $name." : подстраницы";
        $this->pageHeader = $name." : подстраницы";

        $total = $this->db->GetOne ("Select Count(*) From `{$this->object}` Where destination='$destination'", 0);
        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_ADDLINK" => "<a href='{$this->pageUrl}?ocd=new' title='Добавить подстраницу'><img src='./images/add.gif' border='0'></a>",
            "HEAD_ORDER" => "Порядок",
            "HEAD_NAME" => "Заголовок страницы",
            "HEAD_TITLE" => "Название страницы в меню",
            "MAIN_PAGES" => $this->Pages_GetLinks ($total, $this->pageUrl."?"),
        );

        $bgcolor = "";
        if ($total > 0)
        {
            $result = $this->db->ExecuteSql ("Select * From `{$this->object}` Where destination='$destination' Order By order_index Asc", true);
            while ($row = $this->db->FetchInArray ($result))
            {
                $id = $row['page_id'];
                $p_order = $row['order_index'];
                $title = $row['title'];
                $menu_title = $row['menu_title'];

                if ($total == 1)
                {
                    $orderLink = "&nbsp;";    
                }
                elseif ($p_order == $total)
                {
                    $orderLink = "<a href='{$this->pageUrl}?ocd=up&id=$id'><img src='./images/arrow_up.gif' align='absmiddle' width='12' border='0' alt='Вверх'></a>";
                }
                elseif ($p_order == 1)
                {
                     $orderLink = "<a href='{$this->pageUrl}?ocd=down&id=$id'><img src='./images/arrow_down.gif' align='absmiddle' width='12' border='0' alt='Вниз'></a>";
                }
                else
                {
                    $orderLink = "<a href='{$this->pageUrl}?ocd=up&id=$id'><img src='./images/arrow_up.gif' align='absmiddle' width='12' border='0' alt='Вверх'></a>";
                    $orderLink .= "<br><a href='{$this->pageUrl}?ocd=down&id=$id'><img src='./images/arrow_down.gif' align='absmiddle' width='12' border='0' alt='Вниз'></a>";
                }
                $activeLink = "<a href='{$this->pageUrl}?ocd=activate&id=$id'><img src='./images/active".$row['is_active'].".gif' width='14' border='0' alt='Изменить статус активности'></a>";
                $editLink = "<a href='{$this->pageUrl}?ocd=edit&id=$id'><img src='./images/edit.gif' width='15' border='0' alt='Редактировать'></a>";
                $delLink = "<a href='{$this->pageUrl}?ocd=del&id=$id' onClick=\"return confirm ('Вы действительно хотите удалить эту страницу?');\"><img src='./images/garbage.gif' width='13' border='0' alt='Удалить'></a>";

                $bgcolor = ($bgcolor == "") ? "#E7E7E7" : "";
                $this->data ['TABLE_ROW'][] = array (
                    "ROW_ORDER" => $p_order,
                    "ROW_TITLE" => $title,
                    "ROW_MENU" => $menu_title,
                    "ROW_ORDERLINK" => $orderLink,
                    "ROW_ACTIVELINK" => $activeLink,
                    "ROW_EDITLINK" => $editLink,
                    "ROW_DELLINK" => $delLink,
                    "ROW_BGCOLOR" => $bgcolor,
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
        $this->mainTemplate = "./templates/page_details.tpl";
        $id = $this->GetGP ("id");
        $this->javaScripts = $this->GetJavaScript ();
        switch ($source)
        {
            case FORM_FROM_DB:
                $row = $this->db->GetEntry ("Select * From {$this->object} Where page_id='$id'", $this->pageUrl);
                $title = "<input type='text' name='title' value='".$row["title"]."' maxlength='120' style='width: 300px;' class='one_line'>";
                $menu_title = "<input type='text' name='menu_title' value='".$row["menu_title"]."' maxlength='120' style='width: 300px;' class='one_line'>";
                $content = "<textarea rows='14' style='width: 450px; height: 340px;' id='content' name='content'>".$row["content"]."</textarea>";
                $keywords = "<input type='text' name='keywords' value='".$row["keywords"]."' maxlength='250' style='width: 300px;' class='one_line'>";
                $description = "<input type='text' name='description' value='".$row["description"]."' maxlength='250' style='width: 300px;' class='one_line'>";


            break;

            case FORM_FROM_GP:
                $title = "<input type='text' name='title' value='".$this->GetGP ("title")."' maxlength='120' style='width: 300px;' class='one_line'>";
                $menu_title = "<input type='text' name='menu_title' value='".$this->GetGP ("menu_title")."' maxlength='120' style='width: 300px;' class='one_line'>";
                $content = "<textarea rows='14' style='width: 450px; height: 340px;' id='content' name='content'>".$this->GetGP ("content")."</textarea>";
                $keywords = "<input type='text' name='keywords' value='".$this->GetGP ("keywords")."' maxlength='250' style='width: 300px;' class='one_line'>";
                $description = "<input type='text' name='description' value='".$this->GetGP ("description")."' maxlength='250' style='width: 300px;' class='one_line'>";
            break;

            case FORM_EMPTY:
            default:
                $title = "<input type='text' name='title' value='' maxlength='120' style='width: 300px;' class='one_line'>";
                $content = "<textarea rows='14' style='width: 450px; height: 340px;' id='content' name='content'></textarea>";
                $menu_title = "<input type='text' name='menu_title' value='' maxlength='120' style='width: 300px;' class='one_line'>";
                $keywords = "<input type='text' name='keywords' value='' maxlength='250' style='width: 300px;' class='one_line'>";
                $description = "<input type='text' name='description' value='' maxlength='250' style='width: 300px;' class='one_line'>";
            break;
        }

        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_ACTION" => $this->pageUrl,
            "MAIN_TITLE" => $title,
            "MAIN_TITLE_ERROR" => $this->GetError ("title"),

            "MAIN_TITLE_MENU" => $menu_title,
            "MAIN_TITLE_MENU_ERROR" => $this->GetError ("menu_title"),

            "MAIN_CONTENT" => $content,
            "MAIN_KEYWORDS" => $keywords,
            "MAIN_DESCRIPTION" => $description,

            "MAIN_CANCEL_URL" => $this->pageUrl,
            "MAIN_ID" => $id,
            "MAIN_OCD" => $opCode,
        );
    }

    //--------------------------------------------------------------------------
    function ocd_new ()
    {
        $destination = $this->GetStateValue ("destination", 0);
        $name = $this->dec ($this->db->GetOne ("Select title From `{$this->object}` Where page_id='$destination' And destination=0", "no"));
        if ($name == "no") $this->Redirect ("pages.php");

        $this->pageTitle = $name.": режим добавления подстраницы";
        $this->pageHeader = $name.": режим добавления подстраницы";
        
        $this->fill_form ("insert", FORM_EMPTY);
    }

    //--------------------------------------------------------------------------
    function ocd_insert ()
    {
        $destination = $this->GetStateValue ("destination", 0);
        $name = $this->dec ($this->db->GetOne ("Select title From `{$this->object}` Where page_id='$destination' And destination=0", "no"));
        if ($name == "no") $this->Redirect ("pages.php");

        $this->pageTitle = $name.": режим добавления подстраницы";
        $this->pageHeader = $name.": режим добавления подстраницы";

        $title = $this->enc ($this->GetValidGP ("title", "Заголовок страницы", VALIDATE_NOT_EMPTY));
        $menu_title = $this->enc ($this->GetValidGP ("menu_title", "Название страницы в меню", VALIDATE_NOT_EMPTY));
        $content = $this->enc ($this->GetGP ("content"));
        $keywords = $this->enc ($this->GetGP ("keywords"));
        $description = $this->enc ($this->GetGP ("description"));

        if ($this->errors['err_count'] > 0) {
            $this->fill_form ("insert", FORM_FROM_GP);
        }
        else
        {
            if ($this->errors['err_count'] > 0)
            {
                $this->fill_form ("insert", FORM_FROM_GP);
            }
            else
            {
                $total = $this->db->GetOne ("Select Count(*) From {$this->object} Where destination='$destination'", 0) + 1;
                $this->db->ExecuteSql ("Insert into {$this->object} (title, menu_title, content, order_index, destination, is_active, keywords, description) values ('$title', '$menu_title', '$content', '$total', '$destination', 0, '$keywords', '$description')");
                $this->Redirect ($this->pageUrl);
            }
        }
    }

    //--------------------------------------------------------------------------
    function ocd_edit ()
    {
        $destination = $this->GetStateValue ("destination", 0);
        $name = $this->dec ($this->db->GetOne ("Select title From `{$this->object}` Where page_id='$destination' And destination=0", "no"));
        if ($name == "no") $this->Redirect ("pages.php");

        $this->pageTitle = $name.": режим редактирования подстраницы";
        $this->pageHeader = $name.": режим редактирования подстраницы";
        $this->fill_form ("update", FORM_FROM_DB);
    }

    //--------------------------------------------------------------------------
    function ocd_update ()
    {
        $destination = $this->GetStateValue ("destination", 0);
        $name = $this->dec ($this->db->GetOne ("Select title From `{$this->object}` Where page_id='$destination' And destination=0", "no"));
        if ($name == "no") $this->Redirect ("pages.php");

        $this->pageTitle = $name.": режим редактирования подстраницы";
        $this->pageHeader = $name.": режим редактирования подстраницы";
        $id = $this->GetGP ("id");
        $title = $this->enc ($this->GetValidGP ("title", "Заголовок страницы", VALIDATE_NOT_EMPTY));
        $menu_title = $this->enc ($this->GetValidGP ("menu_title", "Название страницы в меню", VALIDATE_NOT_EMPTY));
        $content = $this->enc ($this->GetGP ("content"));
        $keywords = $this->enc ($this->GetGP ("keywords"));
        $description = $this->enc ($this->GetGP ("description"));

        if ($this->errors['err_count'] > 0) {
            $this->fill_form ("update", FORM_FROM_GP);
        }
        else
        {

            $this->db->ExecuteSql ("Update {$this->object} Set keywords='$keywords', description='$description', title='$title', menu_title='$menu_title', content='$content'  Where page_id='$id'");
            $this->Redirect ($this->pageUrl);
        }
    }

    //--------------------------------------------------------------------------
    function ocd_activate ()
    {
        $id = $this->GetGP ("id", 0);
        $this->db->ExecuteSql ("Update {$this->object} Set is_active=(1-is_active) Where page_id='$id'");
        $this->Redirect ($this->pageUrl);
    }

    //--------------------------------------------------------------------------
    function ocd_del ()
    {
        $id = $this->GetGP ("id", 0);

        $destination = $this->GetStateValue ("destination", 0);
        if ($destination == 0) $this->Redirect ("pages.php");

        $p_order = $this->db->GetOne ("Select order_index From `{$this->object}` Where page_id='$id'");
        $this->db->ExecuteSql ("Delete From `{$this->object}` Where page_id='$id'");
        $this->db->ExecuteSql ("Update `{$this->object}` Set order_index=order_index-1 Where destination='$destination' And order_index>'$p_order'");
        $this->Redirect ($this->pageUrl);
    }

    //--------------------------------------------------------------------------
    function ocd_down ()
    {
        $id = $this->GetGP ("id", 0);
        $destination = $this->GetStateValue ("destination", 0);
        if ($destination == 0) $this->Redirect ("pages.php");

        $number = $this->db->GetOne ("Select order_index From {$this->object} Where page_id='$id'", 0);
        $number_next = $number + 1;
        $id_next = $this->db->GetOne ("Select page_id From {$this->object} Where order_index='$number_next' And destination='$destination'", 0);
        $this->db->ExecuteSql ("Update {$this->object} Set order_index=order_index+1 Where page_id='$id'");
        $this->db->ExecuteSql ("Update {$this->object} Set order_index=order_index-1 Where page_id='$id_next'");
        $this->Redirect ($this->pageUrl);
    }

    //--------------------------------------------------------------------------
    function ocd_up ()
    {
        $id = $this->GetGP ("id", 0);
        $destination = $this->GetStateValue ("destination", 0);
        if ($destination == 0) $this->Redirect ("pages.php");
        $number = $this->db->GetOne ("Select order_index From {$this->object} Where page_id='$id'", 0);
        $number_next = $number - 1;
        $id_next = $this->db->GetOne ("Select page_id From {$this->object} Where order_index='$number_next' And destination='$destination'", 0);
        $this->db->ExecuteSql ("Update {$this->object} Set order_index=order_index-1 Where page_id='$id'");
        $this->db->ExecuteSql ("Update {$this->object} Set order_index=order_index+1 Where page_id='$id_next'");
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

$zPage = new ZPage ("main_pages");

$zPage->Render ();

?>