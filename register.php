<?php
require 'db.php';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password_raw = $_POST['password'] ?? '';
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo "email_exists";
    exit;
}
$password = password_hash($password_raw, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $password);
if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}