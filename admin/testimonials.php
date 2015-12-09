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
        $this->orderDefault = "testimonial_id";
        XPage::XPage ($object);
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $this->mainTemplate = "./templates/testimonials.tpl";
        $this->pageTitle = "Отзывы";
        $this->pageHeader = "Отзывы";

        $total = $this->db->GetOne ("Select Count(*) From `{$this->object}`", 0);
        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "HEAD_NAME" => $this->Header_GetSortLink ("name", "Автор"),
			"HEAD_EMAIL" => $this->Header_GetSortLink ("email", "Email"),
			"HEAD_CITY" => $this->Header_GetSortLink ("city", "Город"),
            
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
                $id = $row['testimonial_id'];
                $name = $this->dec ($row['name']);
				$email = $this->dec ($row['email']);
				$city = $this->dec ($row['city']);
                $description = nl2br ($this->dec ($row['description']));
                
                $date = date ("d-m-Y", $row['z_date']);
                
                $activeLink = "<a href='{$this->pageUrl}?ocd=activate&id=$id'><img src='./images/active".$row['is_active'].".gif' width='14' border='0' alt='Изменить статус активности' title='Изменить статус активности'></a>";
                $delLink = "<a href='{$this->pageUrl}?ocd=del&id=$id' onClick=\"return confirm ('Вы действительно хотите удалить этот отзыв?');\"><img src='./images/garbage.gif' width='13' border='0' alt='Удалить' title='Удалить' /></a>";

                $bgcolor = ($bgcolor == "") ? "#E7E7E7" : "";
                $this->data ['TABLE_ROW'][] = array (
                    "ROW_NAME" => $name,
                    "ROW_EMAIL" => $email,
					"ROW_CITY" => $city,
					"ROW_DESCRIPTION" => $description,
					"ROW_DATE" => $date,

                    "ROW_ACTIVELINK" => $activeLink,
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
    function ocd_activate ()
    {
        $id = $this->GetGP ("id", 0);
        $this->db->ExecuteSql ("Update {$this->object} Set is_active=(1-is_active) Where testimonial_id='$id'");
        $this->Redirect ($this->pageUrl);
    }

    //--------------------------------------------------------------------------
    function ocd_del ()
    {
        $id = $this->GetGP ("id", 0);
		$this->db->ExecuteSql ("Delete From {$this->object} Where testimonial_id='$id'");
        $this->Redirect ($this->pageUrl);
    }
}

//------------------------------------------------------------------------------

$zPage = new ZPage ("testimonials");
$zPage->Render ();

?>