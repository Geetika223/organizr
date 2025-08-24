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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Task | Organizr</title>
    <link rel="stylesheet" href="Style/Update.css">
</head>

<body>
    <div class="form-container">
        <h2>Update Task</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $task['id'] ?>">

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($task['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" value="<?= htmlspecialchars($task['date']) ?>" required>
            </div>

            <div class="button-group">
                <button type="submit" name="update" class="btn-primary">Save Changes</button>
                <a href="All_Tasks.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>
