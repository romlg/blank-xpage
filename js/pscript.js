function openPhoto (photo_id)
{
    var dataString = 'ocd=view&photo_id=' + photo_id;
    
    var photo = $.ajax({
    url: "./pmedia.php",
    data: dataString,
    async: false,
    success: function() {
      }
    }).responseText;
    
    $('#mainPhoto').html(photo);
}

function openAudio (audio_id)
{
    var dataString = 'ocd=view&audio_id=' + audio_id;
    
    var audio = $.ajax({
    url: "./amedia.php",
    data: dataString,
    async: false,
    success: function() {
      }
    }).responseText;
    
    $('#mainAudio').html(audio);
}

function openVideo (video_id)
{
    var dataString = 'ocd=view&video_id=' + video_id;
    
    var video = $.ajax({
    url: "./vmedia.php",
    data: dataString,
    async: false,
    success: function() {
      }
    }).responseText;
    
    $('#mainVideo').html(video);
}

function sendComment (id)
{
    var name = "comments_filed" + id;
    $("#" + name).show();
}

function sendAnswer (id)
{
    var name = "answers_filed" + id;
    $("#" + name).show();
}

function changeQ (id)
{
    var txt = '';
    if (txt = window.getSelection) // Not IE, используем метод getSelection
        txt = window.getSelection().toString();
    else // IE, используем объект selection
        txt = document.selection.createRange().text;
    
    $('#quotation' + id).val(txt);
}

function getCode (item_id)
{
    var dataString = 'ocd=code&item_id=' + item_id;
    
    var some = $('#mainCode').html();
    
    if (some == "")
    {
        var code = $.ajax({
        url: "./news.php",
        data: dataString,
        async: false,
        success: function() {
          }
        }).responseText;
    
        $('#mainCode').html(code);
    }
    else
    {
        var code = "";
        $('#mainCode').html(code);
    }
    
}

function sendFriend (item_id)
{
    var dataString = 'ocd=friend&item_id=' + item_id;
    
    var some = $('#mainCode').html();
    
    if (some == "")
    {
        var code = $.ajax({
        url: "./news.php",
        data: dataString,
        async: false,
        success: function() {
          }
        }).responseText;
    
        $('#mainCode').html(code);
    }
    else
    {
        var code = "";
        $('#mainCode').html(code);
    }
}

function sendMail ()
{
    var error = "";
    var name = $('#name').val();
    var email = $('#email').val();
    var email_to = $('#email_to').val();
    var message = $('#message').val();
    
    if (name == "")
    {
        error = error + "Поле 'Ваше имя' не заполнено<br />";
    }
    
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if(reg.test(email) == false) 
    {
        error = error + "Поле 'Ваш Email' имеет неверный формат<br />";
    }
    if(reg.test(email_to) == false) 
    {
        error = error + "Поле 'Email доставки' имеет неверный формат<br />";
    }
    if (message == "")
    {
        error = error + "Поле 'Текст сообщения' не заполнено<br />";
    }
    if (error != "")
    {
        $('#error').html(error);
    }
    else
    {
        $('#sendToFriend').submit();
    }
   
}