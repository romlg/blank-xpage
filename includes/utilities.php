<?php


//------------------------------------------------------------------------------

function getFullComment ($comment_id, $site_id, $member_id, $url, $first = true)
{
    global $db;
    
    $comment = $db->GetEntry ("Select * From `comments` Where `comment_id`='$comment_id'", "./index.php");
    $member = $db->GetEntry ("Select * From `members` Where `member_id`='".$comment ["member_id"]."'", "./index.php");
    
    $avatar = ($member ["avatar"] == "")? "<img src='../data/members/m_icon.png' alt='".$member ["username"]."' title='".$member ["username"]."' />" : "<img src='../data/members/ava_".$member ["avatar"]."' class='site_icon' alt='".$member ["username"]."' title='".$member ["username"]."' />";

    $tab_class = ($first)? "tabInBorder" : "tabInBorder2";
    
    $toRet = "<table width='100%' border='0' cellpadding='0' cellspacing='0' align='left' class='$tab_class'>";
    $toRet .= "<tr>";
    $toRet .= "<td class='tdInBorder'>$avatar</td>";
    $toRet .= "<td class='tdInBorder' width='100%'><H1 class='blog_title'>".$member ["username"]."</H1><p class='description'>".decU ($comment ["message"])."</p></td>";
    $toRet .= "<td class='tdInBorder' align='right' style='white-space:nowrap;'><span class='blog_date'>".date ("d.m.Y H:i", $comment ["z_date"])."</span></td>";
    $toRet .= "</tr>";
    
    $delLink = ($site_id == $member_id And $member_id > 0)? "<a href='".$url."?ocd=delcom&id=".$comment_id."'  class='blogComment' onClick=\"return confirm ('Вы действительно хотите удалить этот комментарий?');\">Удалить</a>" : "";
    
    $answer_link = ($member_id > 0)? "<a href='javascript: sendAnswer (".$comment_id.");' class='blogComment'>Ответить</a>" : "";
    
    $toRet .= "<tr><td class='tdInBorder'></td><td class='tdInBorder' colspan='2' align='right'>".$delLink."&nbsp;&nbsp;".$answer_link."</td></tr>";
    
    $form = "<div class='comments_filed' id='answers_filed$comment_id'><form action='$url' method='POST' style='margin:1px;'>
    <textarea style='height:50px;width:350px;' name='comments'></textarea><br />
    <input type='hidden' name='ocd' value='answer' />
    <input type='hidden' name='comment_id' value='$comment_id' />
    <input type='submit' value=' Ответить ' />
    </form></div>"; 
    
    $toRet .= "<tr><td class='tdInBorder'></td><td colspan='2' align='left' class='tdInBorder'>$form</td></tr>";
    
    $answers = "";
    
    $result = $db->ExecuteSql ("Select `comment_id` From `comments` Where `to_comment_id`='".$comment_id."' Order By `z_date` Asc");
    while ($row = $db->FetchInArray ($result))
    {
        $comment_id_a = $row ["comment_id"];
        
        $answers .= getFullComment ($comment_id_a, $site_id, $member_id, $url, false);   
    }
    $db->FreeSqlResult ($result); 
    
    $toRet .= "<tr><td class='tdInBorder'></td><td colspan='2' align='left' class='tdInBorder'>$answers</td></tr>";
    
    $toRet .= "</table>";
    
    return $toRet;
    
}


//------------------------------------------------------------------------------

function getAudioPlayer ($siteUrl, $file)
{
    return "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' width='96' height='20' id='own_flashplayer' align='middle'>
            <param name='allowScriptAccess' value='sameDomain' />
            <param name='movie' value='".$siteUrl."player/flashplayer_mem.swf?file=$file&startplay=false' /><param name='quality' value='high' /><param name='bgcolor' value='#ffffff' /><embed src='".$siteUrl."player/flashplayer_mem.swf?file=$file&startplay=false' quality='high' bgcolor='#ffffff' width='96' height='20' name='own_flashplayer' align='middle' allowScriptAccess='sameDomain' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />
            </object>";     
}


//------------------------------------------------------------------------------

function make_seed ()
{
    list ($usec, $sec) = explode (' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}

//------------------------------------------------------------------------------

function getUnID ($length)
{
    $toRet = "";
    $symbols = array ();
    for ($i = 0; $i < 26; $i++)
        $symbols[] = chr (97 + $i);
    for ($i = 0; $i < 10; $i++)
        $symbols[] = chr (48 + $i);

    srand (make_seed());
    for ($i = 0; $i < $length; $i++)
        $toRet .= $symbols[rand (0, 35)];
    return $toRet;
}

//------------------------------------------------------------------------------

function getUserAgent ($user_agent)
{
    $browser = "unknown";

    if (eregi ("Opera", $user_agent)) $broser = "Opera";

    if (eregi ("MSIE", $user_agent)) $browser = "MS Internet Explorer";

    if (eregi ("Netscape", $user_agent)) $browser = "Netscape";

    if (eregi ("Mozilla", $user_agent) and !eregi ("MSIE", $user_agent)) $browser = "Mozilla";

    return $browser;
} 

//------------------------------------------------------------------------------

function getJsString ($value)
{
    $search = array ("/'/", "/\"/", "/\r\n/", "/\n/");
    $replace = array ("\'", "\\\"", " ", " ");
    return preg_replace ($search, $replace, $value);
}

//------------------------------------------------------------------------------

function getMonthSelect ($value = "", $name = "dateMonth", $straif = 0)
{
    if ($value == "" Or $value == 0) $value = date ("m")+$straif;
    if ($value > 12) $value = $value-12;
    if ($value < 1) $value = $value+12;
    $toRet = "<select name='$name' class='select_line'>";

    if ($value == 1) $check = "selected"; else $check = "";
    $toRet .= "<option value='1' $check>Январь</option>";
    if ($value == 2) $check = "selected"; else $check = "";
    $toRet .= "<option value='2' $check>Февраль</option>";
    if ($value == 3) $check = "selected"; else $check = "";
    $toRet .= "<option value='3' $check>Март</option>";
    if ($value == 4) $check = "selected"; else $check = "";
    $toRet .= "<option value='4' $check>Апрель</option>";
    if ($value == 5) $check = "selected"; else $check = "";
    $toRet .= "<option value='5' $check>Май</option>";
    if ($value == 6) $check = "selected"; else $check = "";
    $toRet .= "<option value='6' $check>Июнь</option>";
    if ($value == 7) $check = "selected"; else $check = "";
    $toRet .= "<option value='7' $check>Июль</option>";
    if ($value == 8) $check = "selected"; else $check = "";
    $toRet .= "<option value='8' $check>Август</option>";
    if ($value == 9) $check = "selected"; else $check = "";
    $toRet .= "<option value='9' $check>Сентябрь</option>";
    if ($value == 10) $check = "selected"; else $check = "";
    $toRet .= "<option value='10' $check>Октябрь</option>";
    if ($value == 11) $check = "selected"; else $check = "";
    $toRet .= "<option value='11' $check>Ноябрь</option>";
    if ($value == 12) $check = "selected"; else $check = "";
    $toRet .= "<option value='12' $check>Декабрь</option>";

    return $toRet."</select>";
}

//------------------------------------------------------------------------------

function getYearSelect ($value = "", $name = "dateYear", $table = "", $field = "")
{
    global $db;
    $toRet = "<select name='$name'>";
    if ($value == "" Or $value == 0) $value = date ("Y");
    $start = date("Y") - 80;
    if ($value < $start) $start = $value - 1;
    if ($table != "" And $field != "")
    {
        $start = $db->GetOne ("Select Min($field) From $table");
        $start = date ("Y", $start);
    }

    for ($i = $start; $i <= (date ("Y") + 5); $i++)
    {
        if ($value == $i) $check = "selected"; else $check = "";
        $toRet .= "<option value='$i' $check> $i </option>";
    }

    return $toRet."</select>";
}

//------------------------------------------------------------------------------

function getDaySelect ($value = "", $name = "dateDay")
{
    if ($value == "" Or $value == 0) $value = date ("d");
    $toRet = "<select name='$name' class='select_line'>";

    for ($i = 1; $i < 32; $i++)
    {
        if ($value == $i) $check = "selected"; else $check = "";
        if (strlen ($i) == 1) $i = "0".$i;
        $toRet .= "<option value='$i' $check > $i </option>";
    }

    return $toRet."</select>";
}

//------------------------------------------------------------------------------

function getDays ($month , $year)
{
    switch ($month)
    {
        case 1:
            $days = 31;
        break;
        case 2:
            $days = (floor ($year / 4) == $year / 4)? 29 : 28;
        break;
        case 3:
            $days = 31;
        break;
        case 4:
            $days = 30;
        break;
        case 5:
            $days = 31;
        break;
        case 6:
            $days = 30;
        break;
        case 7:
            $days = 31;
        break;
        case 8:
            $days = 31;
        break;
        case 9:
            $days = 30;
        break;
        case 10:
            $days = 31;
        break;
        case 11:
            $days = 30;
        break;
        case 12:
            $days = 31;
        break;
        default:
            $days = 30;
    }
    return $days;
}

//------------------------------------------------------------------------------

function getMonthConvert ($value = "")
{
    $value = str_replace("Jan", "Январь", $value);
    $value = str_replace("Feb", "Февраль", $value);
    $value = str_replace("Mar", "Март", $value);
    $value = str_replace("Apr", "Апрель", $value);
    $value = str_replace("May", "Май", $value);
    $value = str_replace("Jun", "Июнь", $value);
    $value = str_replace("Jul", "Июль", $value);
    $value = str_replace("Aug", "Август", $value);
    $value = str_replace("Sep", "Сентябрь", $value);
    $value = str_replace("Oct", "Октябрь", $value);
    $value = str_replace("Nov", "Ноябрь", $value);
    $value = str_replace("Dec", "Декабрь", $value);

    return $value;
}

//------------------------------------------------------------------------------

function sendMail ($email, $subject, $message, $header)
{
    $header .= "Content-type: text/plain; charset=utf-8\r\n";
    @mail ($email, $subject, $message, $header);
    return true;
}

//------------------------------------------------------------------------------

function makeThumbnail ($nameFull, $size)
{
    global $db;
    $quality='100';
    $info = getimagesize ($nameFull);
    if ($size == 1)
    {
        $logoMaxWidth = 660;
        $logoMaxHeight = 660;
    }
    else if ($size == 0)
    {
        $logoMaxWidth = 150;
        $logoMaxHeight = 150;
    }
    else if ($size == 2)
    {
        $logoMaxWidth = 100;
        $logoMaxHeight = 100;
    }
    else if ($size == 3)
    {
        $logoMaxWidth = 50;
        $logoMaxHeight = 50;
    }
    else if ($size == 4)
    {
        $logoMaxWidth = 600;
        $logoMaxHeight = 600;
    }
    if ($info[0] > $logoMaxWidth or $info[1] > $logoMaxHeight)
    {
        $im = imagecreatefromjpeg ($nameFull);
        $k1 = $logoMaxWidth / imagesx ($im);
        $k2 = $logoMaxHeight / imagesy ($im);
        $k = $k1 > $k2 ? $k2 : $k1;
        $w = intval (imagesx ($im) * $k);
        $h = intval (imagesy ($im) * $k);
        $im1 = imagecreatetruecolor ($w, $h);
        imagecopyresampled ($im1, $im, 0, 0, 0, 0, $w, $h, imagesx($im), imagesy($im));
        imagejpeg ($im1, $nameFull, $quality);
        imagedestroy ($im);
        imagedestroy ($im1);
        return true;
    }
}

//------------------------------------------------------------------------------

function makeThumbnailg ($nameFull, $size)
{
    global $db;
    $quality='100';
    $info = getimagesize ($nameFull);
    if ($size == 1)
    {
        $logoMaxWidth = $db->GetSetting ("gPhotoBigMaxWidth");
        $logoMaxHeight = $db->GetSetting ("gPhotoBigMaxHeight");
    }
    else if ($size == 0)
    {
        $logoMaxWidth = $db->GetSetting ("gPhotoSmallMaxWidth");
        $logoMaxHeight = $db->GetSetting ("gPhotoSmallMaxHeight");
    }
    if ($info[0] > $logoMaxWidth or $info[1] > $logoMaxHeight)
    {
        $im = imagecreatefromjpeg ($nameFull);
        $k1 = $logoMaxWidth / imagesx ($im);
        $k2 = $logoMaxHeight / imagesy ($im);
        $k = $k1 > $k2 ? $k2 : $k1;
        $w = intval (imagesx ($im) * $k);
        $h = intval (imagesy ($im) * $k);
        $im1 = imagecreatetruecolor ($w, $h);
        imagecopyresampled ($im1, $im, 0, 0, 0, 0, $w, $h, imagesx($im), imagesy($im));
        imagejpeg ($im1, $nameFull, $quality);
        imagedestroy ($im);
        imagedestroy ($im1);
        return true;
    }
}

//------------------------------------------------------------------------------

function send_mime_mail ($name_from, // имя отправителя
                        $email_from, // email отправителя
                        $name_to, // имя получателя
                        $email_to, // email получателя
                        $data_charset, // кодировка переданных данных
                        $send_charset, // кодировка письма
                        $subject, // тема письма
                        $body // текст письма
                        ) {
  $to = mime_header_encode($name_to, $data_charset, $send_charset)
                 . ' <' . $email_to . '>';
  $subject = mime_header_encode($subject, $data_charset, $send_charset);
  $from =  mime_header_encode($name_from, $data_charset, $send_charset)
                     .' <' . $email_from . '>';
  if($data_charset != $send_charset) {
        $body = iconv($data_charset, $send_charset, $body);
  }
  $headers = "From: $from\r\n";
  $headers .= "Content-type: text/plain; charset=$send_charset\r\n";

  return mail($to, $subject, $body, $headers);
}

//------------------------------------------------------------------------------

function mime_header_encode($str, $data_charset, $send_charset) {
  if($data_charset != $send_charset) {
    $str = iconv($data_charset, $send_charset, $str);
  }
  return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
}

function decU ($value)
    {
        $search = array ("/&amp;/", "/&lt;/", "/&gt;/", "/&#039;/");
        $replace = array ("&", "<", ">", "'");
        return preg_replace ($search, $replace, $value);
    }
    
function getAmountDays ($date)
{ 
    $month = date('m', $date);
    $year = date('Y', $date);
    
    if ($month == "01" Or $month == "03" Or$month == "05" Or$month == "07" Or$month == "08" Or$month == "10" Or$month == "12")
    {
        $days = 31;
    }
    elseif ($month == "04" Or $month == "06" Or $month == "09" Or $month == "11")
    {
        $days = 30;
    }
    else
    {
        if ($year == 2004 Or $year == 2008 Or $year == 2012 Or $year == 2016 Or $year == 2020 Or $year == 2024)
        $days = 29;
        else 
        $days = 28;    
    }
    
    return $days;
}    

function getProperDate ($date)
{
    $month = date ("M", $date);
    $year = date ("Y", $date);
    
    $month =  getMonthConvert ($month);
    
    return $month." ".$year;
    
}

?>