<?php

require_once ("./includes/config.php");
require_once ("./includes/xtemplate.php");
require_once ("./includes/utilities.php");
require_once ("./includes/xpage_public.php");

//------------------------------------------------------------------------------

class ZPage extends XPage
{
    //--------------------------------------------------------------------------
    function ZPage ($object)
    {
        XPage::XPage ($object);
        $this->mainTemplate = "./templates/contentwin.tpl";
        
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $page_id = $this->GetID("p_id", 1);
        $sub_page_id = $this->GetID("s_id", 0);
        
        if ($sub_page_id == 0)
        {
            $row = $this->db->GetEntry ("Select title, content From `main_pages` Where page_id='$page_id'", "index.php");
            $title = $this->dec ($row ['title']);
            $content = $this->dec ($row ['content']);
        }
        else
        {
            $row = $this->db->GetEntry ("Select title, content From `main_pages` Where page_id='$sub_page_id'", "index.php");
            $title = $this->dec ($row ['title']);
            $content = $this->dec ($row ['content']);
        }
        
        $this->pageTitle = $title;
        $this->pageHeader = $title;
        
        $message = "<textarea name='message' rows='6' style='width: 350px;'></textarea>";
        $email = "<input type='text' name='email' value='' maxlength='50' style='width: 250px;' />";
        $name = "<input type='text' name='name' value='' maxlength='50' style='width: 250px;' />";
        
        $mess = "";
        if ($this->GetGp ("res", "") == "ok")  $mess = "Спасибо. Ваше сообщение успешно отправлено.";
        
        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_CONTENT" => $content,
            "MAIN_MESS" => $mess,
            "MAIN_MESSAGE" => $message,
            "MAIN_NAME" => $name,
            "MAIN_EMAIL" => $email,
            "MAIN_URL" => $this->siteUrl
            
            
        );
        
        if ($page_id == 2 And $sub_page_id == 0)
        {
            $this->data ['FORM'][] = array ("_" => "_");
            
            
        }
    }
    
    //--------------------------------------------------------------------------
    function ocd_send ()
    {
        $message = $this->GetValidGP ("message", "Сообщение", VALIDATE_NOT_EMPTY);
        $name = $this->GetValidGP ("name", "Имя", VALIDATE_NOT_EMPTY);
        $email = $this->GetValidGP ("email", "Email", VALIDATE_EMAIL);
        
        if ($this->errors['err_count'] > 0)
        {
            $this->mainTemplate = "./templates/content.tpl";
        
            
            $title = $this->dec ($this->db->GetOne ("Select title From `main_pages` Where page_id='2'", ""));
            $content = $this->dec ($this->db->GetOne ("Select content From `main_pages` Where page_id='2'", ""));
            
            $this->pageTitle = $title;
            $this->pageHeader = $title;
            
            $message = "<textarea name='message' rows='6' style='width: 350px;'>$message</textarea>";
            $email = "<input type='text' name='email' value='$email' maxlength='50' style='width: 250px;' />";
            $name = "<input type='text' name='name' value='$name' maxlength='50' style='width: 250px;' />";
            
            $this->data = array (
                "MAIN_HEADER" => $this->pageHeader,
                "MAIN_CONTENT" => $content,
                "MAIN_MESSAGE" => $message,
                "MAIN_MESSAGE_ERROR" => $this->GetError ("message"),
            
                "MAIN_NAME" => $name,
                "MAIN_NAME_ERROR" => $this->GetError ("name"),
            
                "MAIN_EMAIL" => $email,
                "MAIN_EMAIL_ERROR" => $this->GetError ("email"),
                
                "MAIN_URL" => $this->siteUrl
            
            );
            
            $this->data ['FORM'][] = array ("_" => "_");
            
        }
        else
        {
            
			header('Content-type: text/html; charset=utf-8');

            $full_message = "Запрос с сайта: \r\n";
            $full_message .= "Имя отправителя: ".$name."\r\n";
            $full_message .= "Email отправителя: ".$email."\r\n";
            $full_message .= "Сообщение: \r\n";
            $full_message .= $message;
            
            $a = send_mime_mail ($name, $email, "Admin", $this->adminEmail, "CP1251", "UTF-8", "Запрос со страницы контактов сайта", $full_message );
            if ($a)
            {
                $this->Redirect ($this->siteUrl."ok");
            }
            else
            {
                $this->Redirect ($this->siteUrl."2");
            }
            
        }

    }
}

//------------------------------------------------------------------------------

$zPage = new ZPage ("index");

$zPage->Render ();

?>