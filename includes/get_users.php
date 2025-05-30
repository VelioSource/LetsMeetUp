<?php

$info = (object)[];

$query = "SELECT 
            users.user_id, users.username, users.gender, users.email, 
            users.location_lat, users.location_lon,
            user_preferences.interests, 
            user_preferences.nationality, 
            user_preferences.sociability
          FROM users
          JOIN user_preferences ON users.user_id = user_preferences.user_id";

$result = $DB->read($query);

if (is_array($result)) {
    $users = [];

    foreach ($result as $row) {
        // Exclude current user (optional)
        if ($row->user_id === $_SESSION['userid']) {
            continue;
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
        ];
    }

    echo json_encode($users);
} else {
    $info->message = "No users found";
    $info->data_type = "error";
    echo json_encode($info);
}
