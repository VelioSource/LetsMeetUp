<?php
file_put_contents("login_debug.log", json_encode($DATA_OBJ) . "\n", FILE_APPEND);


$info = (object)[];
$error = "";

// validate inputs
$data = [];
$data['email'] = $DATA_OBJ->email;

if (empty($DATA_OBJ->email)) {
    $error = "Please enter a valid email";
}

if (empty($DATA_OBJ->password)) {
    $error = "Please enter a valid password";
}

if ($error == "") {
    $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $result = $DB->read($query, $data);
if (!$result) {
  file_put_contents("log.txt", "Login query failed for email: " . $data['email'] . "\n", FILE_APPEND);
}

file_put_contents("debug_login.txt", print_r($result, true));

    if (is_array($result)) {
        $result = $result[0];
        error_log("Login attempt for email: " . $data['email']);
error_log("Provided password: " . $DATA_OBJ->password);
error_log("Stored hash: " . $result->password);
error_log("Verify result: " . (password_verify($DATA_OBJ->password, $result->password) ? "TRUE" : "FALSE"));


        // ✅ Secure password check
        if (password_verify($DATA_OBJ->password, $result->password)) {

            $_SESSION['userid'] = $result->user_id;

            $info->message = "You're successfully logged in!";
            $info->data_type = "info";
            echo json_encode($info);
            die;
        } else {
            $info->message = "Wrong email or password!";
            $info->data_type = "error";
            echo json_encode($info);
            die;
        }
    } else {
        $info->message = "Wrong email or password!";
        $info->data_type = "error";
        echo json_encode($info);
        die;
    }

} else {
    $info->message = $error;
    $info->data_type = "error";
    echo json_encode($info);
}
