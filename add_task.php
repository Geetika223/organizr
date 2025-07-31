<?php
require 'db.php';
session_start();
$user_id = $_SESSION['user_id'];
$title = $_POST['title'];
$desc = $_POST['description'];
$due = $_POST['due_date'];
$sql = "INSERT INTO tasks (user_id, title, description, due_date) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $user_id, $title, $desc, $due);
echo $stmt->execute() ? "added" : "error";
?>
