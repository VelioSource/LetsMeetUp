<?php

$info = (object)[];
$me = $_SESSION['userid'];
$DB = new Database();

// --- Send Friend Request ---
if ($DATA_OBJ->data_type == "add_friend") {
    $target_id = $DATA_OBJ->user_id;

    // Prevent duplicate requests
    $check = $DB->read("SELECT * FROM friends 
                        WHERE sender_id = '$me' AND receiver_id = '$target_id' 
                        OR sender_id = '$target_id' AND receiver_id = '$me' LIMIT 1");
    if (!$check) {
        $query = "INSERT INTO friends (sender_id, receiver_id, status) 
                  VALUES ('$me', '$target_id', 'pending')";
        $DB->write($query);
    }

    $info->message = "Friend request sent.";
    $info->data_type = "add_friend";
    echo json_encode($info);
    die;
}

// --- Accept Friend Request ---
if ($DATA_OBJ->data_type == "accept_friend") {
    $sender_id = $DATA_OBJ->user_id;

    $query = "UPDATE friends 
              SET status = 'accepted' 
              WHERE sender_id = '$sender_id' AND receiver_id = '$me'";
    $DB->write($query);

    $info->message = "Friend request accepted.";
    $info->data_type = "accept_friend";
    echo json_encode($info);
    die;
}

// --- Get Incoming Friend Requests ---
if ($DATA_OBJ->data_type == "get_requests") {
    $query = "SELECT sender_id FROM friends 
              WHERE receiver_id = '$me' AND status = 'pending'";
    $rows = $DB->read($query);

    $info->requests = [];
    if (is_array($rows)) {
        foreach ($rows as $row) {
            $info->requests[] = $row->sender_id;
        }
    }

    $info->data_type = "friend_requests";
    echo json_encode($info);
    die;
}

// --- Get Friends List ---
if ($DATA_OBJ->data_type == "get_friends") {
    $query = "SELECT * FROM friends 
              WHERE (sender_id = '$me' OR receiver_id = '$me') 
              AND status = 'accepted'";
    $rows = $DB->read($query);

    $friends = [];
    if (is_array($rows)) {
        foreach ($rows as $row) {
            $friend_id = ($row->sender_id == $me) ? $row->receiver_id : $row->sender_id;
            $friends[] = $friend_id;
        }
    }

    $info->friends = $friends;
    $info->data_type = "friends_list";
    echo json_encode($info);
    die;
}

// --- Decline Friend Request ---
if ($DATA_OBJ->data_type == "decline_friend") {
    $sender_id = $DATA_OBJ->user_id;

    $query = "DELETE FROM friends 
              WHERE sender_id = '$sender_id' AND receiver_id = '$me' AND status = 'pending'";
    $DB->write($query);

    $info->message = "Friend request declined.";
    $info->data_type = "decline_friend";
    echo json_encode($info);
    die;
}

