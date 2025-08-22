<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "organizr";

$con = mysqli_connect($server, $username, $password, $database);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM task WHERE id = $id";
    mysqli_query($con, $sql);
}

mysqli_close($con);

// redirect back
header("Location: All_Tasks.php");
exit();
?>