<?php
$insert = false;
if (isset($_POST["title"])) {
    $server = "localhost";
    $username = "root";
    $password = "";
    $con = mysqli_connect($server, $username, $password);
    if (!$con) {
        die("Connection to this database failed due to" . mysqli_connect_error());
    }
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $sql = "INSERT INTO `organizr`.`task` (`Title`, `Description`, `Date`) VALUES ('$title', '$description', '$date')";
    if ($con->query($sql) == true) {
        $insert = true;
    } else {
        echo "ERROR: $sql <br> $con->error";
    }
    $con->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Organizr - Add Tasks</title>
    <link rel="stylesheet" href="Style/Home.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <button id="toggle-btn"><i class="fas fa-bars"></i></button>
    <div id="sidebar" class="hide">
        <div class="offcanvas-body">
            <h1 class="text-xs"><i class="fa-solid fa-layer-group"></i>Organizr</h1>
            <a class="iconsonbar button text-muted" href="Home.php">
                <i class="con fas fa-search p-2"></i>Home
            </a>
            <a class="iconsonbar button text-muted" href="Search.php">
                <i class="con fas fa-search p-2"></i>Search
            </a>
            <a class="iconsonbar button text-muted" href="#" id="sidebar-add-task">
                <i class="con fas fa-plus p-2"></i>Add Task
            </a>
            <hr>
            <p class="fw-semibold mtdd">My Tasks</p>
            <a class="text-start iconsonbar2" href="All_Tasks.php">
                <i class="fas fa-check icon-gap"></i>All Tasks
            </a>
        </div>
    </div>

    <div id="main-content">
        <header>
            <div class="logo">
                <i class="fa-solid fa-layer-group"></i> Organizr
            </div>
            <div class="right-header">
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"><i
                            class="fa-solid fa-download"></i>Export</button>
                    <ul class="dropdown-menu X">
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-print"></i>Print todo-list</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-file-pdf"></i>Save as PDF</a></li>
                    </ul>
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"><i
                            class="fa-solid fa-hand-pointer"></i>Select</button>
                    <ul class="dropdown-menu Y">
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-list"></i>List View</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-table"></i>Tiles View</a></li>
                    </ul>
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"><i
                            class="fa-solid fa-arrow-down-short-wide"></i>Filtering</button>
                    <ul class="dropdown-menu Z">
                        <li><a class="dropdown-item" href="#">Filters</a></li>
                        <div id="filter" class="filter-section">
                            <span class="close-modal" id="close-filter">&times;</span>
                            <h4>Progress</h4>
                            <label><input type="checkbox" class="checkbox"><i class="fa-solid fa-clock"></i>Incomplete
                                tasks</label>
                            <label><input type="checkbox" class="checkbox"><i
                                    class="fa-solid fa-person-walking-arrow-right"></i>Marked in progress</label>
                            <label><input type="checkbox" class="checkbox"><i
                                    class="fa-solid fa-check-to-slot"></i>Completed tasks</label>
                        </div>
                        <div class="filter-section">
                            <h4>Due Date</h4>
                            <label><input type="checkbox" class="checkbox"><i
                                    class="fa-solid fa-flag-checkered"></i>Overdue</label>
                            <label><input type="checkbox" class="checkbox"><i class="fa-solid fa-flag-checkered"></i>Due
                                Today</label>
                            <label><input type="checkbox" class="checkbox"><i class="fa-solid fa-flag-checkered"></i>Due
                                Next
                                <input type="number" min="0" value="1">days</label>
                        </div>
                    </ul>
                </div>
            </div>
        </header>

        <main>
            <div class="task-panel">
                <h2>All Tasks</h2>
                <button class="add-btn" id="show-task-form">+ Add Task</button>
                <form id="task-form" style="display:none;" action="Home.php" method="post">
                    <input type="text" id="title" name="title" placeholder="Title" required />
                    <textarea id="description" name="description" placeholder="Description" required></textarea>
                    <input type="date" name="date" id="due_date" required />
                    <button type="submit">Submit</button>
                    <?php
                    if ($insert == true) {
                        echo '<p> Task Created.</p>';
                    }
                    ?>
                </form>
            </div>
        </main>
        <!-- footer -->
        <footer class="tm-footer">
            <div class="tm-footer-main">
                <div class="tm-logo">
                    <i class="fa-solid fa-layer-group"></i>Organizr
                </div>
                <div class="tm-col">
                    <a href="Home.php">
                        <h4>Home</h4><br>
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
    </div>
    <!-- LOGIN PAGE -->
    <div id="login-modal" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <span class="close-modal" id="close-login-modal">&times;</span>
            <h1>Organizr</h1>
            <h4>Sign in to Organizr</h4>
            <form id="login-form" action="Home.php" method="post">
                <label for="login_email">Email</label>
                <input type="email" id="login_email" name="login_email" placeholder="Enter your email" required>
                <label for="login_password">Password</label>
                <input type="password" id="login_password" name="login_password" placeholder="Enter your password"
                    required>
                <button type="submit" class="add-btn" style="width:100%;margin-bottom: 1.5rem;">Login</button>
                <p style="text-align: center;" class="create-account">Don't have an account? <a href="#"
                        id="switch-to-register">Create Account</a></p>
            </form>
        </div>
    </div>
    <!-- REGISTRATION PAGE -->
    <div id="create-account-modal" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <span class="close-modal" id="close-create-account-modal">&times;</span>
            <h1>Organizr</h1>
            <h4>Create a new account</h4>
            <form id="register-form" action="Home.php" method="post">
                <label for="register_name">Full Name</label>
                <input type="text" id="register_name" name="register_name" placeholder="Enter your full name" required>
                <label for="register_email">Email</label>
                <input type="email" id="register_email" name="register_email" placeholder="Enter your email" required>
                <label for="register_password">Password</label>
                <input type="password" id="register_password" name="register_password" placeholder="Create a password"
                    required>
                <label for="register_confirm_password">Confirm Password</label>
                <input type="password" id="register_confirm_password" name="register_confirm_password"
                    placeholder="Confirm your password" required>
                <button type="submit" class="add-btn" style="width:100%; margin-bottom: 1.5rem;">Create Account</button>
                <p style="text-align: center;" class="forgot-password">Already have an account? <a href="#"
                        id="switch-to-login">Sign in</a></p>
            </form>
        </div>
    </div>
    <script src="Script/Home.js"></script>
    <script src="Script/sidebar.js"></script>
</body>

</html>