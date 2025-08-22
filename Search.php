<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "organizr";

$con = mysqli_connect($server, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$search = "";
$result = false;

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = $_GET['search'];

    $sql = "SELECT * FROM task WHERE Title LIKE ? OR Description LIKE ? ORDER BY Date ASC";
    $stmt = mysqli_prepare($con, $sql);

    $search_param = "%" . $search . "%";

    mysqli_stmt_bind_param($stmt, "ss", $search_param, $search_param);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>All Tasks - Organizr</title>
    <link rel="stylesheet" href="Style/Search.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" />
</head>

<body>
    <button id="toggle-btn" aria-label="Toggle sidebar"><i class="fas fa-bars"></i></button>
    <div id="sidebar" class="hide">
        <div class="offcanvas-body">
            <h1 class="text-xs"><i class="fa-solid fa-layer-group"></i> Organizr</h1>
            <nav>
                <a class="iconsonbar button text-muted" href="Home.php" id="open-search-modal">
                    <i class="con fas fa-search p-2"></i>Home
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
            </nav>
        </div>
    </div>

    <div id="main-content">
        <header>
            <div class="logo">
                <i class="fa-solid fa-layer-group"></i> Organizr
            </div>
            <div class="header-search-form">
                <form id="header-search-form" method="GET"
                    action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="text" name="search" placeholder="Search for tasks..."
                        value="<?= htmlspecialchars($search) ?>" />
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </header>

        <main>
            <div class="task-panel">
                <h2><?php echo !empty($search) ? "Search Results for \"" . htmlspecialchars($search) . "\"" : "All Tasks"; ?>
                </h2>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    echo '<ul class="task-list">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<li class="task-item">';
                        echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                        echo '<p>' . nl2br(htmlspecialchars($row['description'])) . '</p>';
                        echo '<small>Due: ' . htmlspecialchars($row['date']) . '</small>';
                        echo '</li>';
                    }
                    echo '</ul>';
                } elseif (!empty($search)) {
                    echo '<p>No tasks found matching your search query.</p>';
                } else {
                    $all_tasks_sql = "SELECT * FROM task ORDER BY Date ASC";
                    $all_tasks_result = mysqli_query($con, $all_tasks_sql);

                    if (mysqli_num_rows($all_tasks_result) > 0) {
                        echo '<ul class="task-list">';
                        while ($row = mysqli_fetch_assoc($all_tasks_result)) {
                            echo '<li class="task-item">';
                            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                            echo '<p>' . nl2br(htmlspecialchars($row['description'])) . '</p>';
                            echo '<small>Due: ' . htmlspecialchars($row['date']) . '</small>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p>No tasks found.</p>';
                    }
                }
                ?>
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
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </footer>
    </div>
    <script src="Script/sidebar.js"></script>
    <?php
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
    mysqli_close($con);
    ?>
</body>

</html>