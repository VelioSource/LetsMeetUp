<?php

$info = (object)[];
if (!isset($_SESSION['userid'])) {
  $info->message = "Not logged in.";
  $info->data_type = "error";
  echo json_encode($info);
  die;
}

$user_id = $_SESSION['userid'];
$interests = implode(',', $DATA_OBJ->interests ?? []);
$nationality = $DATA_OBJ->nationality ?? '';
$location = $DATA_OBJ->location ?? '';
$sociability = $DATA_OBJ->sociability ?? '';
$purpose = $DATA_OBJ->purpose ?? '';

$query = "INSERT INTO user_preferences 
          (user_id, interests, nationality, location, sociability, purpose) 
          VALUES 
          (:user_id, :interests, :nationality, :location, :sociability, :purpose)
          ON DUPLICATE KEY UPDATE 
          interests = VALUES(interests),
          nationality = VALUES(nationality),
          location = VALUES(location),
          sociability = VALUES(sociability),
          purpose = VALUES(purpose)";


$params = [
  'user_id' => $user_id,
  'interests' => $interests,
  'nationality' => $nationality,
  'location' => $location,
  'sociability' => $sociability,
  'purpose' => $purpose
];

$result = $DB->write($query, $params);

if ($result) {
  $info->message = "Preferences saved.";
  $info->data_type = "info";
} else {
  $info->message = "Failed to save preferences.";
  $info->data_type = "error";
}

echo json_encode($info);
