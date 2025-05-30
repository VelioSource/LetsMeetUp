<?php

$info = (object)[];

if (!isset($_SESSION['userid'])) {
    $info->message = "User not logged in.";
    $info->data_type = "error";
    echo json_encode($info);
    die;
}

$data = [];
$data['location_lat'] = floatval($DATA_OBJ->location_lat ?? 0);
$data['location_lon'] = floatval($DATA_OBJ->location_lon ?? 0);
$data['user_id'] = $_SESSION['userid'];

$query = "UPDATE users SET location_lat = :location_lat, location_lon = :location_lon WHERE user_id = :user_id LIMIT 1";
$DB->write($query, $data);

$info->message = "Location updated.";
$info->data_type = "info";
echo json_encode($info);
