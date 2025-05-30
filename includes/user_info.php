<?php

$info = (object)[];
$error = "";

// Ensure session has user
$data = [];
$data['user_id'] = $_SESSION['userid'] ?? null;

if (!$data['user_id']) {
    $info->message = "Not logged in.";
    $info->data_type = "error";
    echo json_encode($info);
    die;
}

// Run JOIN query to fetch profile and preferences
$query = "SELECT users.*, user_preferences.interests, user_preferences.nationality, user_preferences.sociability
          FROM users
          JOIN user_preferences ON users.user_id = user_preferences.user_id
          WHERE users.user_id = :user_id
          LIMIT 1";

$result = $DB->read($query, $data);

if (is_array($result)) {
    $result = $result[0];
    $result->data_type = "user_info";

    // Check if profile image exists
    $default = ($result->gender == "Male") ? "./ui/images/user_male.jpg" : "./ui/images/user_female.jpg";
    $image = file_exists($result->image) ? $result->image : $default;
    $result->image = $image;

    echo json_encode($result);
} else {
    $info->message = "User not found.";
    $info->data_type = "error";
    echo json_encode($info);
}
