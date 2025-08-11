<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "organizr";
$con = mysqli_connect($server, $username, $password, $database);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT * FROM task ORDER BY Date ASC";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>All Tasks - Organizr</title>
    <link rel="stylesheet" href="Style/All_Tasks.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" />
</head>

<body>

    <!-- NAV BAR START -->
    <button id="toggle-btn"><i class="fas fa-bars"></i></button>
    <div id="sidebar" class="hide">
        <div class="offcanvas-body">
            <h1 class="text-xs"><i class="fa-solid fa-layer-group"></i> Organizr</h1>
            <a class="iconsonbar button text-muted" href="timesheet.php">
                <i class="con fas fa-stopwatch p-2"></i>Timesheet
            </a>
            <a class="iconsonbar button text-muted" href="Search.php" id="open-search-modal">
                <i class="con fas fa-search p-2"></i>Search
            </a>
            <a class="iconsonbar button text-muted" href="Home.php" id="sidebar-add-task">
                <i class="con fas fa-plus p-2"></i>Add Task
            </a>
            <hr>
            <p class="fw-semibold mtdd">My Tasks</p>
            <a class="text-start iconsonbar2" href="All_Tasks.php">
                <i class="fas fa-check icon-gap"></i>All Tasks
            </a>
        </div>
    </div>
    <!-- NAV BAR END -->

    <div id="main-content">
        <header>
            <div class="logo">
                <i class="fa-solid fa-layer-group"></i> Organizr
            </div>
        </header>

        <main>
            <div class="task-panel">
                <h2>All Tasks</h2>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <ul class="task-list">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <li class="task-item">
                                <h3><?= htmlspecialchars($row['Title']) ?></h3>
                                <p><?= nl2br(htmlspecialchars($row['Description'])) ?></p>
                                <small>Due: <?= htmlspecialchars($row['Date']) ?></small>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No tasks found.</p>
                <?php endif; ?>
                <?php mysqli_close($con); ?>
            </div>
        </main>

        <footer class="tm-footer">
            <div class="tm-footer-main">
                <div class="tm-footer-left">
                    <div class="tm-logo">
                        <i class="fa-solid fa-layer-group"></i> Organizr
                    </div>
                </div>
                <div class="tm-footer-links">
                    <div class="tm-col">
                        <a href="Home.php">
                            <h4>Home</h4>
                        </a>
                        <a href="About_Us.html">About us</a><br>
                        <a href="Blog.html">Blog</a><br>
                        <a href="Careers.html">Careers</a>
                    </div>
                    <div class="tm-col">
                        <h4>Support</h4><br>
                        <a href="Contact.php">Contact</a><br>
                        <a href="Help_Centre.html">Help Center</a><br>
                        <a href="Privacy_Policy.html">Privacy Policy</a>
                    </div>
                </div>
            </div>
            <div class="tm-footer-right tm-bottom">
                <p>&copy; 2025 tm. All rights reserved.</p>
                <div class="tm-social">
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.getElementById("toggle-btn").addEventListener("click", function() {
            document.getElementById("sidebar").classList.toggle("hide");
        });
    </script>

</body>

</html>