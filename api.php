<?php

session_start();

$DATA_RAW= file_get_contents("php://input");
$DATA_OBJ= json_decode($DATA_RAW);

$info=(object)[];
//check if logged in
if(!isset($_SESSION['userid']))
{
    if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type != "login" && $DATA_OBJ->data_type != "signup")
    {
        $info->logged_in = false;
        echo json_encode($info);
        die;
    }
   
}
require_once("classes/initialise.php");
$DB = new Database();


$error= "";
//process the data
if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "signup") 
{
    //signup
    include("includes/signup.php");
}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "login")
{
    //login
    include("includes/login.php");
}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "logout"){

    include("includes/logout.php");
} elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "user_info")
{
    //userinfo
    include("includes/user_info.php");
}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "contacts")
{
    //contacts
    include("includes/contacts.php");
}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "chat")
{
    //chat
    include("includes/chat.php");
}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "settings")
{
    //settings
    include("includes/settings.php");
}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "save_settings")
{
    //settings
    include("includes/save_settings.php");
} 
elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "send_message")
{
    //send message to database

    include("includes/send_message.php");
} 

function message_left($result)
{
    return "
    <div id='message_left'>
    <div></div>
        <img src=$result->image alt='user'>
        <b>$result->username</b><br>
        This is a test message<br><br>
        <span style='font-size:10px;color:#6f6666;'>20 Jan 2020 10:00 am<span>
    </div> ";
}

function message_right($result)
{
    return "
    <div id='message_right'>
    <div></div>
        <img src=$result->image alt='user' style='float:right;'>
        <b>$result->username</b><br><br>
        This is a test message<br>
        <span style='font-size:10px;color:#fff2f;'>20 Jan 2020 10:00 am<span>
    </div>";

}
