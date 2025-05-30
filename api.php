<?php

session_start();

$DATA_RAW = file_get_contents("php://input");
$DATA_OBJ = json_decode($DATA_RAW);

$info = (object)[];

if (!$DATA_OBJ || !isset($DATA_OBJ->data_type)) {
    $info->message = "Invalid request.";
    $info->data_type = "error";
    echo json_encode($info);
    die;
}
// Check if logged in unless it's login/signup
if (!isset($_SESSION['userid'])) {
    if (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type != "login" && $DATA_OBJ->data_type != "signup") {
        $info->logged_in = false;
        echo json_encode($info);
        die;
    }
}

require_once("classes/initialise.php");
$DB = new Database();

$error = "";

// ROUTE REQUESTS

if (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "signup") {
    include("includes/signup.php");

} elseif (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "login") {
    include("includes/login.php");

} elseif (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "logout") {
    include("includes/logout.php");

} elseif (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "user_info") {
    include("includes/user_info.php");

} elseif (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "contacts") {
    include("includes/contacts.php");

} elseif (isset($DATA_OBJ->data_type) && ($DATA_OBJ->data_type == "chat" || $DATA_OBJ->data_type == "chat_refresh")) {
    include("includes/chat.php");

} elseif (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "settings") {
    include("includes/settings.php");

} elseif (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "save_settings") {
    include("includes/save_settings.php");

} elseif (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "send_message") {
    include("includes/send_message.php");

} elseif (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "delete_message") {
    include("includes/delete_message.php");

} elseif (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "delete_thread") {
    include("includes/delete_thread.php");

} elseif (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "questionnaire") {
    include("includes/questionnaire.php");

} elseif (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "get_users") {
    include("includes/get_users.php");
    die;

} elseif (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "update_location") {
    include("includes/update_location.php");

} elseif (in_array($DATA_OBJ->data_type, [
    "add_friend", "accept_friend", "get_requests", "get_friends", "decline_friend"
])) {
    include("includes/friends.php");
}


// =============== UI MESSAGE HELPERS =============== //

function message_left($data, $result)
{
    $image = ($result->gender == "Male") ? "./ui/images/user_male.jpg" : "./ui/images/user_female.jpg";
    if (file_exists($result->image)) {
        $image = $result->image;
    }

    $a = "
    <div id='message_left'>
    <div></div>
    <img id='prof_img' src='$image' alt='user'>
    <b>$result->username</b><br>
    $data->message<br><br>";

    if ($data->files != "" && file_exists($data->files)) {
        $a .= "<img src='$data->files' style='width: 60%;cursor:pointer;' onclick='image_show(event)'/><br>";
    }

    $a .= "<span style='font-size:10px;color:#6f6666;'>" . date("jS M Y H:i:s", strtotime($data->date)) . "<span>
    <img id='trash' src='./ui/icons/bin-icon.png' alt='delete' onclick='delete_message(event)' msgid='$data->id'/>
    </div>";

    return $a;
}

function message_right($data, $result)
{
    $image = ($result->gender == "Male") ? "./ui/images/user_male.jpg" : "./ui/images/user_female.jpg";
    if (file_exists($result->image)) {
        $image = $result->image;
    }

    $a = "
    <div id='message_right'>
    <div>";

    if ($data->seen) {
        $a .= "<img src='./ui/images/blue-tick.png'/>";
    } elseif ($data->received) {
        $a .= "<img src='./ui/images/grey-tick.png'/>";
    }

    $a .= "</div>
    <img id='prof_img' src='$image' alt='user' style='float:right;'>
    <b>$result->username</b><br>
    $data->message<br><br>";

    if ($data->files != "" && file_exists($data->files)) {
        $a .= "<img src='$data->files' style='width: 60%;cursor:pointer;' onclick='image_show(event)'/><br>";
    }

    $a .= "<span style='font-size:10px;color:#fff2f;'>" . date("jS M Y H:i:s", strtotime($data->date)) . "<span>
    <img id='trash' src='./ui/icons/bin-icon.png' alt='delete' onclick='delete_message(event)' msgid='$data->id'/>
    </div>";

    return $a;
}

function message_controls()
{
    return "
        </div>    
        <span onclick='delete_thread(event)' style='color:grey;cursor:pointer;'>Delete messages</span>
        <div style='display:flex; width:100%;height:40px;'>
            <label for='message_file'><img src='./ui/icons/clip.png' style='opacity:0.8;width:30px; height:30px; cursor: pointer; margin:5px;'></label>
            <input type='file' id='message_file' name='file' style='display:none' onchange='send_image(this.files)'/>
            <input id='message_text' onkeyup='enter_pressed(event)' style='flex:6;border: solid thin #ccc; border-bottom:none;;font-size:14px;padding:4px;' type='text' placeholder='Type your message...'>
            <input style='flex:1;cursor:pointer;' type='button' value='send' onclick='send_message(event)'>
        </div>
        </div>";
}
