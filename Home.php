<?php
session_start();

$server = "localhost";
$username = "root";
$password = "";
$database = "organizr";

$con = mysqli_connect($server, $username, $password, $database);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$insert = false;
$login_error = null;
$registration_success = false;

function close_resources($stmt, $con)
{
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
    if (isset($con)) {
        mysqli_close($con);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['login_email'])) {
        $email = trim($_POST['login_email']);
        $password = trim($_POST['login_password']);

        $login_sql = "SELECT id, full_name, password FROM users WHERE email = ?";
        $stmt_login = mysqli_prepare($con, $login_sql);
        mysqli_stmt_bind_param($stmt_login, "s", $email);
        mysqli_stmt_execute($stmt_login);
        $result = mysqli_stmt_get_result($stmt_login);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['full_name'];

                unset($_SESSION['guest_tasks']);

                header("Location: Home.php");
                exit;
            } else {
                $login_error = "Invalid email or password.";
            }
        } else {
            $login_error = "Invalid email or password.";
        }
        mysqli_stmt_close($stmt_login);
    }

    if (isset($_POST['register_name'])) {
        $full_name = trim($_POST['register_name']);
        $email = trim($_POST['register_email']);
        $password = trim($_POST['register_password']);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $check_sql = "SELECT id FROM users WHERE email = ?";
        $stmt_check = mysqli_prepare($con, $check_sql);
        mysqli_stmt_bind_param($stmt_check, "s", $email);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $login_error = "An account with this email already exists.";
        } else {
            $register_sql = "INSERT INTO `users` (`full_name`, `email`, `password`) VALUES (?, ?, ?)";
            $stmt_register = mysqli_prepare($con, $register_sql);
            mysqli_stmt_bind_param($stmt_register, "sss", $full_name, $email, $hashed_password);

            if (mysqli_stmt_execute($stmt_register)) {
                $registration_success = true;
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = mysqli_insert_id($con);
                $_SESSION['user_name'] = $full_name;

                if (isset($_SESSION['guest_tasks'])) {
                    $user_id = $_SESSION['user_id'];
                    $insert_guest_sql = "INSERT INTO `task` (`Title`, `Description`, `Date`, `user_id`) VALUES (?, ?, ?, ?)";
                    $stmt_guest = mysqli_prepare($con, $insert_guest_sql);
                    foreach ($_SESSION['guest_tasks'] as $guest_task) {
                        mysqli_stmt_bind_param($stmt_guest, "sssi", $guest_task['title'], $guest_task['description'], $guest_task['date'], $user_id);
                        mysqli_stmt_execute($stmt_guest);
                    }
                    mysqli_stmt_close($stmt_guest);
                    unset($_SESSION['guest_tasks']);
                }

                header("Location: Home.php");
                exit;
            } else {
                echo "ERROR: " . mysqli_error($con);
            }
            mysqli_stmt_close($stmt_register);
        }
        mysqli_stmt_close($stmt_check);
    }

    if (isset($_POST['title'])) {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $date = trim($_POST['date']);

        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
            $user_id = $_SESSION['user_id'];
            $sql = "INSERT INTO `task` (`Title`, `Description`, `Date`, `user_id`) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $date, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                $insert = true;
            } else {
                echo "ERROR: " . mysqli_error($con);
            }
            mysqli_stmt_close($stmt);
        } else {
            if (!isset($_SESSION['guest_tasks'])) {
                $_SESSION['guest_tasks'] = [];
            }
            if (count($_SESSION['guest_tasks']) < 2) {
                $_SESSION['guest_tasks'][] = [
                    'title' => $title,
                    'description' => $description,
                    'date' => $date,
                ];
                $insert = true;
            } else {
                $insert = false;
                $login_error = "You can only add 2 tasks without signing in. Please log in or create an account to add more.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Organizr - Add Tasks</title>
    <link rel="stylesheet" href="Home.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <button id="toggle-btn"><i class="fas fa-bars"></i></button>
    <div id="sidebar" class="hide">
        <div class="offcanvas-body">
            <h1 class="text-xs"><i class="fa-solid fa-layer-group"></i>Organizr</h1>
            <a class="iconsonbar button text-muted" href="Timesheet.php">
                <i class="con fas fa-stopwatch p-2"></i>Timesheet
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
            <?php if (isset($_SESSION['loggedin'])): ?>
                <a class="iconsonbar button text-muted" href="logout.php">
                    <i class="con fas fa-sign-out-alt p-2"></i>Logout
                </a>
            <?php endif; ?>
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
                            <label><input type="checkbox" class="checkbox"><i class="fa-solid fa-clock"></i>Incomplete tasks</label>
                            <label><input type="checkbox" class="checkbox"><i class="fa-solid fa-person-walking-arrow-right"></i>Marked in progress</label>
                            <label><input type="checkbox" class="checkbox"><i class="fa-solid fa-check-to-slot"></i>Completed tasks</label>
                        </div>
                        <div class="filter-section">
                            <h4>Due Date</h4>
                            <label><input type="checkbox" class="checkbox"><i class="fa-solid fa-flag-checkered"></i>Overdue</label>
                            <label><input type="checkbox" class="checkbox"><i class="fa-solid fa-flag-checkered"></i>Due Today</label>
                            <label><input type="checkbox" class="checkbox"><i class="fa-solid fa-flag-checkered"></i>Due Next
                                <input type="number" min="0" value="1">days</label>
                        </div>
                    </ul>
                </div>
                <?php if (isset($_SESSION['loggedin'])): ?>
                    <a href="logout.php" class="btn btn-outline-success me-2" type="button">
                        <i class="fa-regular fa-circle-user"></i> Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-success me-2" id="open-login-modal" type="button">
                        <i class="fa-regular fa-circle-user"></i> Sign In
                    </button>
                <?php endif; ?>
            </div>
        </header>

        <main>
            <div class="task-panel">
                <h2>All Tasks</h2>
                <?php if ($insert): ?>
                    <div class="alert alert-success">Your task has been added successfully!</div>
                <?php endif; ?>
                <?php if ($login_error): ?>
                    <div class="alert alert-danger"><?= $login_error ?></div>
                <?php endif; ?>

                <?php if (!isset($_SESSION['loggedin'])): ?>
                    <p class="info-line">
                        You can add up to 2 tasks without signing in. To save and track more tasks, please create an account.
                    </p>
                <?php endif; ?>


                <button class="add-btn" id="show-task-form">+ Add Task</button>
                <form id="task-form" style="display:none;" action="Home.php" method="post">
                    <input type="text" id="title" name="title" placeholder="Title" required />
                    <textarea id="description" name="description" placeholder="Description" required></textarea>
                    <input type="date" name="date" id="due_date" required />
                    <button type="submit">Submit</button>
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
            <?php if ($login_error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($login_error) ?></div>
            <?php endif; ?>
            <form id="login-form" action="Home.php" method="post">
                <label for="login_email">Email</label>
                <input type="email" id="login_email" name="login_email" placeholder="Enter your email" required>
                <label for="login_password">Password</label>
                <input type="password" id="login_password" name="login_password" placeholder="Enter your password" required>
                <button type="submit" class="add-btn" style="width:100%;margin-bottom: 1.5rem;">Login</button>
                <p style="text-align: center;" class="create-account">Don't have an account? <a href="#" id="switch-to-register">Create Account</a></p>
            </form>
        </div>
    </div>
    <!-- REGISTRATION PAGE -->
    <div id="create-account-modal" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <span class="close-modal" id="close-create-account-modal">&times;</span>
            <h1>Organizr</h1>
            <h4>Create a new account</h4>
            <?php if ($registration_success): ?>
                <div class="alert alert-success">Registration successful! You are now logged in.</div>
            <?php endif; ?>
            <form id="register-form" action="Home.php" method="post">
                <label for="register_name">Full Name</label>
                <input type="text" id="register_name" name="register_name" placeholder="Enter your full name" required>
                <label for="register_email">Email</label>
                <input type="email" id="register_email" name="register_email" placeholder="Enter your email" required>
                <label for="register_password">Password</label>
                <input type="password" id="register_password" name="register_password" placeholder="Create a password" required>
                <label for="register_confirm_password">Confirm Password</label>
                <input type="password" id="register_confirm_password" name="register_confirm_password" placeholder="Confirm your password" required>
                <button type="submit" class="add-btn" style="width:100%; margin-bottom: 1.5rem;">Create Account</button>
                <p style="text-align: center;" class="forgot-password">Already have an account? <a href="#" id="switch-to-login">Sign in</a></p>
            </form>
        </div>
    </div>
    <script src="Script/Home.js"></script>
    <script src="Script/sidebar.js"></script>
    <?php close_resources(null, $con); ?>
</body>

</html>