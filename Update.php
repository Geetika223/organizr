<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "organizr";

$con = mysqli_connect($server, $username, $password, $database);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $date = mysqli_real_escape_string($con, $_POST['date']);

    $sql = "UPDATE task SET title='$title', description='$description', date='$date' WHERE id=$id";
    mysqli_query($con, $sql);

    header("Location: All_Tasks.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($con, "SELECT * FROM task WHERE id=$id");
    $task = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Update Task</title>
    <link rel="stylesheet" href="Style/Update.css" class="css">
</head>

<body>
    <h2>Update Task</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $task['id'] ?>">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>"><br><br>
        <label>Description:</label><br>
        <textarea name="description"><?= htmlspecialchars($task['description']) ?></textarea><br><br>
        <label>Date:</label><br>
        <input type="date" name="date" value="<?= htmlspecialchars($task['date']) ?>"><br><br>
        <button type="submit" name="update">Save Changes</button>
    </form>
</body>

</html>