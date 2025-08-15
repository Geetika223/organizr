<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "organizr";
$con = mysqli_connect($server, $username, $password, $database);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $title = mysqli_real_escape_string($con, $_POST['title']);
            $description = mysqli_real_escape_string($con, $_POST['description']);
            $date = mysqli_real_escape_string($con, $_POST['date']);

            $sql = "INSERT INTO task (Title, Description, Date) VALUES ('$title', '$description', '$date')";
            mysqli_query($con, $sql);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        if ($_POST['action'] === 'delete' && isset($_POST['title'])) {
            $title = mysqli_real_escape_string($con, $_POST['title']);
            $sql = "DELETE FROM task WHERE Title = '$title'";
            mysqli_query($con, $sql);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        if ($_POST['action'] === 'update' && isset($_POST['old_title'])) {
            $old_title = mysqli_real_escape_string($con, $_POST['old_title']);
            $new_title = mysqli_real_escape_string($con, $_POST['title']);
            $description = mysqli_real_escape_string($con, $_POST['description']);
            $date = mysqli_real_escape_string($con, $_POST['date']);
            $sql = "UPDATE task SET Title='$new_title', Description='$description', Date='$date' WHERE Title='$old_title'";
            mysqli_query($con, $sql);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}
$sql = "SELECT * FROM task ORDER BY Date ASC";
$result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Tasks | Organizr</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Style/Timesheet.css" />
</head>

<body>
    <button id="toggle-btn"><i class="fas fa-bars"></i></button>
    <div id="sidebar" class="hide">
        <div class="offcanvas-body">
            <h1 class="text-xs"><i class="fa-solid fa-layer-group"></i> Organizr</h1>
            <a class="iconsonbar button text-muted" href="Timesheet.php"><i class="con fas fa-stopwatch p-2"></i>Timesheet</a>
            <a class="iconsonbar button text-muted" href="Search.php"><i class="con fas fa-search p-2"></i>Search</a>
            <a class="iconsonbar button text-muted" href="Home.php"><i class="con fas fa-plus p-2"></i>Add Task</a>
            <hr>
            <p class="fw-semibold mtdd">My Tasks</p>
            <a class="text-start iconsonbar2" href="All_Tasks.php"><i class="fas fa-check icon-gap"></i>All Tasks</a>
        </div>
    </div>
    <div class="main-wrapper">
        <div class="task-panel">
            <h2>Manage Tasks</h2>

            <button class="add-task-btn" id="toggle-form-btn">+ Add New Task</button>

            <form id="task-form" method="POST" action="">
                <input type="hidden" name="action" value="add" />
                <input type="text" name="title" placeholder="Task Title" required />
                <textarea name="description" placeholder="Description"></textarea>
                <input type="date" name="date" required />
                <button type="submit">Add Task</button>
            </form>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <ul class="task-list">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <li class="task-item" data-title="<?= htmlspecialchars($row['Title'], ENT_QUOTES) ?>">
                            <h3><?= htmlspecialchars($row['Title']) ?></h3>
                            <p><?= nl2br(htmlspecialchars($row['Description'])) ?></p>
                            <small>Due: <?= htmlspecialchars($row['Date']) ?></small>

                            <button class="show-update-btn">Update</button>

                            <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this task?');" style="display:inline;">
                                <input type="hidden" name="action" value="delete" />
                                <input type="hidden" name="title" value="<?= htmlspecialchars($row['Title'], ENT_QUOTES) ?>" />
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>

                            <form method="POST" action="" class="update-form" style="display:none;">
                                <input type="hidden" name="action" value="update" />
                                <input type="hidden" name="old_title" value="<?= htmlspecialchars($row['Title'], ENT_QUOTES) ?>" />
                                <input type="text" name="title" value="<?= htmlspecialchars($row['Title'], ENT_QUOTES) ?>" required />
                                <textarea name="description" required><?= htmlspecialchars($row['Description'], ENT_QUOTES) ?></textarea>
                                <input type="date" name="date" value="<?= htmlspecialchars($row['Date']) ?>" required />
                                <button type="submit" class="update-btn">Save</button>
                                <button type="button" class="cancel-update-btn">Cancel</button>
                            </form>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No tasks found.</p>
            <?php endif; ?>
        </div>
    </div>
    <footer class="tm-footer">
        <div class="tm-footer-main">
            <div class="tm-logo"><i class="fa-solid fa-layer-group"></i> Organizr</div>
            <div class="tm-col">
                <a href="Home.php">
                    <h4>Home</h4>
                </a>
                <a href="About_Us.html">About us</a><br>
                <a href="Blog.html">Blog</a><br>
                <a href="Careers.html">Careers</a><br>
            </div>
            <div class="tm-col">
                <h4>Support</h4><br>
                <a href="Contact.php">Contact</a><br>
                <a href="Help_Centre.html">Help Center</a><br>
                <a href="Privacy_Policy.html">Privacy Policy</a>
            </div>
        </div>
        <div class="tm-footer-bottom">
            <p>&copy; 2025 tm. All rights reserved.</p>
            <div class="tm-social">
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </footer>

    <script>
        const toggleBtn = document.getElementById('toggle-form-btn');
        const form = document.getElementById('task-form');
        toggleBtn.addEventListener('click', () => {
            if (form.style.display === 'block') {
                form.style.display = 'none';
                toggleBtn.textContent = '+ Add New Task';
            } else {
                form.style.display = 'block';
                toggleBtn.textContent = 'Cancel';
            }
        });

        const sidebarToggle = document.getElementById('toggle-btn');
        const sidebar = document.getElementById('sidebar');
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('hide');
        });

        document.querySelectorAll('.show-update-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const li = btn.closest('li.task-item');
                const updateForm = li.querySelector('.update-form');

                document.querySelectorAll('.update-form').forEach(f => {
                    if (f !== updateForm) f.style.display = 'none';
                });

                if (updateForm.style.display === 'block') {
                    updateForm.style.display = 'none';
                } else {
                    updateForm.style.display = 'block';
                }
            });
        });

        document.querySelectorAll('.cancel-update-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const form = btn.closest('form.update-form');
                form.style.display = 'none';
            });
        });
    </script>
</body>

</html>

<?php mysqli_close($con); ?>