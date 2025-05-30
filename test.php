<?php
$inputPassword = "password";
$storedHash = '$2y$10$ZMZEFebuK3pxtbJvYTNJh..Y0sofek1/svdW2NY1ZBT7PRbSeOFuW'; // From DB

if (password_verify($inputPassword, $storedHash)) {
    echo "Match!";
} else {
    echo "Wrong!";
}
