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
        $this->mainTemplate = "./templates/testimonials.tpl";
        $this->pageTitle = "Отзывы";
        $this->pageHeader = "Отзывы";
        
            $text = "";
            if (array_key_exists ('Log_Turing_ID', $_SESSION))
            {
                $text = $_SESSION['Log_Turing_ID'];
            }
            $_SESSION['Log_Turing_ID_Old'] = $text;
            $text = "";
            for ($i = 0; $i < 5; $i++)
                $text .= rand (1, 9);
            $_SESSION['Log_Turing_ID'] = $text;
        
        
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $mess = "";
        $rez = $this->GetGP ("rez", "");
        if ($rez == "ok") $mess = "<span class='message'>Спасибо. Ваше сообщение зарегистрировано в книге Отзывов.</span>";
        
        $description = "<textarea name='description' rows='4' style='width: 420px;'></textarea>";
        $name = "<input type='text' name='name' value='' maxlength='250' style='width: 250px;'>";
		$city = "<input type='text' name='city' value='' maxlength='250' style='width: 250px;'>";
        $email = "<input type='text' name='email' value='' maxlength='250' style='width: 250px;'>";
        
        $total = $this->db->GetOne ("Select Count(*) From `testimonials` Where is_active=1");
        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_PAGES" => $this->Pages_GetLinks ($total, $this->pageUrl."?"),
            "MAIN_NAME" => $name,
            "MAIN_NAME_ERROR" => $this->GetError ("name"),
            
            "MAIN_CITY" => $city,
            
            "MAIN_EMAIL" => $email,
            "MAIN_EMAIL_ERROR" => $this->GetError ("email"),
            "MAIN_DESCRIPTION" => $description,
            "MAIN_DESCRIPTION_ERROR" => $this->GetError ("description"),
            "MAIN_MESS" => $mess,
            
            "LOGIN_TURING" => "<input type='text' name='turing' value='' maxlength='12' style='width: 120px;' autocomplete='off'>",
            "LOGIN_TURING_IMAGE" => "<img src='".$this->siteUrl."includes/turing.php?PHPSESSID=".session_id()."' border='0' align='absmiddle' style='border: 3px double #6A5ACD;height:22px;'>",
            "LOGIN_TURING_ERROR" => $this->GetError ("turing"),
            
        );

        $bgcolor = "";
        if ($total > 0)
        {
            $result = $this->db->ExecuteSql ("Select * From `testimonials` Where is_active=1 Order By z_date Desc", true);
            
            while ($row = $this->db->FetchInArray ($result))
            {
                $name = $this->dec ($row['name']);
                $email = $this->dec ($row['email']);
                $city = $this->dec ($row['city']);
                
                $z_date = date ('d.m.Y', $row['z_date']);
                $email = $this->dec ($row ["email"]);
                $description = nl2br ($this->dec ($row ["description"]));
                
                $this->data ['TABLE_ROW'][] = array (
                    "ROW_NAME" => $name,
                    "ROW_CITY" => $city,
                    "ROW_DATE" => $z_date,
                    "ROW_EMAIL" => $email,
                    "ROW_MESSAGE" => $description,
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
    function ocd_send ()
    {
        $name = $this->enc ($this->GetValidGP ("name", "Имя", VALIDATE_NOT_EMPTY));
        $email = $this->enc ($this->GetValidGP ("email", "Email", VALIDATE_EMAIL));
        $city = $this->GetGP ("city", "");
        $description = $this->enc ($this->GetValidGP ("description", "Сообщение", VALIDATE_NOT_EMPTY));
        
        $turingNumbers = $_SESSION['Log_Turing_ID_Old'];
        $turing = $this->GetGP ("turing");
        
        if ($turingNumbers != $turing) 
        {
        		$this->SetError ("turing", "Введите правильную последовательность цифр.");
        }
        
        if ($this->errors['err_count'] > 0) 
        {
            $this->ocd_list ();
        }
        else
        {
            $this->db->ExecuteSql ("Insert into {$this->object} (name, city, email, description, z_date, is_active) values ('$name', '$city', '$email', '$description', '".time()."', 1)");
            $this->Redirect ($this->pageUrl."?rez=ok");
        }
        
    }
}

//------------------------------------------------------------------------------

$zPage = new ZPage ("testimonials");

$zPage->Render ();

?>