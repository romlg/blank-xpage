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
        $this->mainTemplate = "./templates/admindetails.tpl";
        $this->pageTitle = "Настройки Администратора";
        $this->pageHeader = "Настройки Администратора";
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $ec = $this->GetGP ("ec");
        $message = ($ec == "yes")? "Ваши настройки были успешно обновлены" : "";

        $adminUsername = $this->db->GetSetting ("a_username", "");
        $adminUsername = "<input type='text' name='AdminUsername' value='$adminUsername' maxlength='12' style='width:160px;' class='one_line'>";

        $adminPassword = "<input type='password' name='AdminPassword' value='' maxlength='14' style='width:160px;' class='one_line'>";
        $adminPassword1 = "<input type='password' name='AdminPassword1' value='' maxlength='14' style='width:160px;' class='one_line'>";
        $currentPassword = "<input type='password' name='CurrentPassword' value='' maxlength='14' style='width:160px;' class='one_line'>";

        $contactEmail = $this->db->GetSetting ("a_email", "");
        $contactEmail = "<input type='text' name='ContactEmail' value='$contactEmail' style='width:300px;' class='one_line'>";
        
        $title = $this->db->GetSetting ("a_title", "");
        $title = "<input type='text' name='a_title' value='$title' style='width:300px;' class='one_line'>";
        
        
        $this->data = array (
            "ACTION_SCRIPT" => $this->pageUrl,
            "MAIN_HEADER" => $this->pageHeader,
            "MAIN_MESSAGE" => $message,
            "MAIN_ADMIN_USERNAME" => $adminUsername,
            "MAIN_ADMIN_PASSWORD" => $adminPassword,
            "MAIN_ADMIN_PASSWORD1" => $adminPassword1,
            "MAIN_CURRENT_PASSWORD" => $currentPassword,
            "MAIN_CONTACTEMAIL" => $contactEmail,
            "MAIN_TITLE" => $title,
            
        );
    }

    //--------------------------------------------------------------------------
    function ocd_update ()
    {
        $title = $this->GetValidGP ("a_title", "Название сайта", VALIDATE_NOT_EMPTY);
        
        $adminUsername = $this->GetValidGP ("AdminUsername", "Логин администратора", VALIDATE_USERNAME);
        $contactEmail = $this->GetValidGP ("ContactEmail", "Контактный Email", VALIDATE_EMAIL);

        $adminPassword = $this->GetGP ("AdminPassword");
        if ($adminPassword != "")
        {
            $adminPassword = $this->GetValidGP ("AdminPassword", "Пароль администратора", VALIDATE_PASSWORD);
            $adminPassword1 = $this->GetValidGP ("AdminPassword1", $adminPassword, VALIDATE_PASS_CONFIRM);
        }

        $currentPassword = md5 ($this->GetGP ("CurrentPassword"));
        $real_passwd = $this->db->GetSetting ("a_password", "");
        if ($currentPassword != $real_passwd)
        {
            $this->SetError ("CurrentPassword", "Текущий пароль администратора введен неверно.");

        }

        
        if ($this->errors['err_count'] > 0)
        {
            
            $this->data = array (
                "ACTION_SCRIPT" => $this->pageUrl,
                "MAIN_HEADER" => $this->pageHeader,
                "MAIN_ADMIN_USERNAME" => "<input type='text' name='AdminUsername' value='$adminUsername' maxlength='12' style='width:160px;' class='one_line'>",
                "MAIN_ADMIN_USERNAME_ERROR" => $this->GetError ("AdminUsername"),
                "MAIN_ADMIN_PASSWORD" => "<input type='password' name='AdminPassword' value='' maxlength='12' style='width:160px;' class='one_line'>",
                "MAIN_ADMIN_PASSWORD_ERROR" => $this->GetError ("AdminPassword"),
                "MAIN_ADMIN_PASSWORD1" => "<input type='password' name='AdminPassword1' value='' maxlength='12' style='width:160px;' class='one_line'>",
                "MAIN_ADMIN_PASSWORD1_ERROR" => $this->GetError ("AdminPassword1"),
                "MAIN_CURRENT_PASSWORD" => "<input type='password' name='CurrentPassword' value='' maxlength='12' style='width:160px;' class='one_line'>",
                "MAIN_CURRENT_PASSWORD_ERROR" => $this->GetError ("CurrentPassword"),
                "MAIN_CONTACTEMAIL" => "<input type='text' name='ContactEmail' value='$contactEmail' size='40' maxlength='120' class='one_line'>",
                "MAIN_CONTACTEMAIL_ERROR" => $this->GetError ("ContactEmail"),
                "MAIN_TITLE" => "<input type='text' name='a_title' value='$title' style='width:300px;' class='one_line'>",
                "MAIN_TITLE_ERROR" => $this->GetError ("a_title"),
                
            );
        }
        else
        {
            $this->db->SetSetting ("a_username", $adminUsername);
            if ($adminPassword != "") 
            {
                $adminPassword = md5 ($adminPassword);
                $this->db->SetSetting ("a_password", $adminPassword);
            }

            $this->db->SetSetting ("a_email", $contactEmail);
            $this->db->SetSetting ("a_title", $title);
            $this->UpdateRegisterDetails ();
            
            $this->Redirect ($this->pageUrl."?ec=yes");
        }
    }
}

//------------------------------------------------------------------------------

$zPage = new ZPage ("settings");

$zPage->Render ();

?>