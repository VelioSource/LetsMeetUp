<?php

$info = (object)[];
$me = $_SESSION['userid'];
$DB = new Database();

// Fetch users + preferences
$query = "SELECT 
            users.user_id, users.username, users.gender, users.email, 
            users.location_lat, users.location_lon,
            users.image,
            user_preferences.interests, 
            user_preferences.nationality, 
            user_preferences.sociability
          FROM users
          JOIN user_preferences ON users.user_id = user_preferences.user_id";

$result = $DB->read($query);

// Preload friend relations
$friends = $DB->read("SELECT * FROM friends WHERE sender_id = '$me' OR receiver_id = '$me'");
$friend_map = [];

if ($friends) {
    foreach ($friends as $f) {
        $is_me_sender = ($f->sender_id == $me);
        $other_id = $is_me_sender ? $f->receiver_id : $f->sender_id;
        $friend_map[$other_id] = [
            'status' => $f->status,
            'direction' => $is_me_sender ? 'sent' : 'received'
        ];
    }
}

if (is_array($result)) {
    $users = [];

    foreach ($result as $row) {
        // Skip self
        if ($row->user_id == $me) {
            continue;
        }

        // Determine friend status
        $status = "none";
        if (isset($friend_map[$row->user_id])) {
            $relation = $friend_map[$row->user_id];
            if ($relation['status'] == 'accepted') {
                $status = "friend";
            } elseif ($relation['status'] == 'pending') {
                $status = $relation['direction'] == 'sent' ? "pending" : "request";
            }
        }

        $users[] = [
            "user_id" => $row->user_id,
            "name" => $row->username,
            "gender" => $row->gender,
            "email" => $row->email,
            "nationality" => $row->nationality,
            "type" => $row->sociability,
            "hobbies" => explode(",", $row->interests ?? ""),
            "lat" => floatval($row->location_lat ?? 0),
            "lon" => floatval($row->location_lon ?? 0),
            "image" => file_exists($row->image) && !empty($row->image)
            ? $row->image
            : ($row->gender == "Female" ? "./ui/images/user_female.jpg" : "./ui/images/user_male.jpg"),
            "friend_status" => $status
        ];
    }

    echo json_encode($users);
} else {
    $info->message = "No users found";
    $info->data_type = "error";
    echo json_encode($info);
}
