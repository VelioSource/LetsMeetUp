<?php

$info = (object)[];
$error = "";
$data = [];

// Auto-generated values
$data['user_id'] = $DB->generate_id(20);
$data['date'] = date('Y-m-d H:i:s');

// === Username ===
$data['username'] = trim($DATA_OBJ->username ?? '');
if (empty($data['username'])) {
    $error .= "Please enter a valid username.<br>";
} else {
    if (strlen($data['username']) < 4) {
        $error .= "Username must be at least 4 characters long.<br>";
    }
    if (!preg_match("/^[a-zA-Z0-9_. -]*$/", $data['username'])) {
        $error .= "Username can only contain letters, numbers, and _ . -<br>";
    }
}

// === Gender ===
$data['gender'] = $DATA_OBJ->gender ?? '';
if (empty($data['gender']) || !in_array($data['gender'], ['Male', 'Female'])) {
    $error .= "Please select a valid gender.<br>";
}

// === Email ===
$data['email'] = trim($DATA_OBJ->email ?? '');
if (empty($data['email'])) {
    $error .= "Please enter a valid email.<br>";
} else {
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $error .= "Invalid email format.<br>";
    } else {
        $email_check = $DB->read("SELECT email FROM users WHERE email = :email", ['email' => $data['email']]);
        if ($email_check) {
            $error .= "This email is already registered.<br>";
        }
    }
}

// === Password ===
$password = $DATA_OBJ->password ?? '';
$password2 = $DATA_OBJ->password2 ?? '';
if (empty($password)) {
    $error .= "Please enter a password.<br>";
} else {
    if (strlen($password) < 8) {
        $error .= "Password must be at least 8 characters long.<br>";
    }
    if ($password !== $password2) {
        $error .= "Passwords do not match.<br>";
    }
    $data['password'] = password_hash($password, PASSWORD_DEFAULT);
}

// === Country ===
$data['country'] = trim($DATA_OBJ->country ?? '');
if (empty($data['country'])) {
    $error .= "Please select or enter your country.<br>";
}

// === Birthday / Age Check ===
$data['birthday'] = trim($DATA_OBJ->birthday ?? '');
if (empty($data['birthday'])) {
    $error .= "Please enter your birthday.<br>";
} else {
    $birthDate = DateTime::createFromFormat('Y-m-d', $data['birthday']);
    $today = new DateTime();

    if (!$birthDate || $birthDate > $today) {
        $error .= "Invalid birthday.<br>";
    } else {
        $age = $today->diff($birthDate)->y;
        if ($age < 13) {
            $error .= "You must be at least 13 years old to sign up.<br>";
        }
    }
}

// === Finalize ===
if ($error === "") {
    $query = "INSERT INTO users (user_id, username, gender, email, password, country, birthday, date) 
              VALUES (:user_id, :username, :gender, :email, :password, :country, :birthday, :date)";
    $result = $DB->write($query, $data);

    if ($result) {
        $_SESSION['userid'] = $data['user_id'];
        $info->message = "Your profile was created.";
        $info->data_type = "info";
    } else {
        $info->message = "Your profile was NOT created due to a database error.";
        $info->data_type = "error";
    }
} else {
    $info->message = $error;
    $info->data_type = "error";
}

echo json_encode($info);
