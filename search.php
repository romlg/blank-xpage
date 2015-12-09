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
        $this->mainTemplate = "./templates/search.tpl";
        
    }

    //--------------------------------------------------------------------------
    function ocd_list ()
    {
        $this->pageTitle = "Поиск тура";
        $this->pageHeader = "Поиск тура";
        $this->javaScripts = $this->GetJavaScript ();
        $this->data = array (
            "MAIN_HEADER" => $this->pageHeader,
            
        );
    }
    
    //--------------------------------------------------------------------------
    function GetJavaScript ()
    {
        return <<<_ENDOFJS_
        <script language='JavaScript' src='./js/search.js'></script>
_ENDOFJS_;
    }
    
}

//------------------------------------------------------------------------------

$zPage = new ZPage ("index");

$zPage->Render ();

?>