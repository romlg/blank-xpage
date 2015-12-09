<?php

require_once ("../includes/config.php");
require_once ("../includes/xtemplate.php");
require_once ("../includes/xpage_admin.php");
require_once ("../includes/utilities.php");

//------------------------------------------------------------------------------

class ZPage extends XPage
{
    //--------------------------------------------------------------------------
    function ZPage ($object = "none")
    {
        XPage::XPage ($object, false);
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $this->mainTemplate = "./templates/login.tpl";
        $this->pageTitle = "Вход в систему";
        $this->pageHeader = "Вход в систему";

        $login = "<input type='text' name='login' value='".$this->GetGP ("login")."' size='16' maxlength='16' class='one_line'>";
        $passwd  = "<input type='password' name='password' value='' size='16' maxlength='16' class='one_line'>";

        $this->data = array (
            "HEADER_TITLE" => $this->pageTitle,
            "HEADER_JAVASCRIPTS" => $this->javaScripts,
            "PAGE_TITLE" => $this->pageHeader,
            "LOGIN_ERROR" => $this->GetError ("login"),
            "LOGIN_USERNAME" => $login,
            "LOGIN_PASSWORD" => $passwd,
        );
    }


    //--------------------------------------------------------------------------
    function ocd_login ()
    {
        
        $result = $this->RegisterUser ();
        switch ($result)
        {
            case 1:
                
                $this->Redirect ("admindetails.php");
            break;
            case -1:
                $this->SetError ("login", "Пароль или логин неверны");
            break;
            default:
               $this->SetError ("login", "Неизвестная ошибка.<br>Попробуйте подключиться позднее.");
        }
        $this->ocd_list ();
    }
}

//------------------------------------------------------------------------------

$zPage = new ZPage ();

$zPage->Render ();

?>