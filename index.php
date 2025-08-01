<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>organizr</title>
    <link rel="stylesheet" href="Style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="main.js" defer></script>
</head>

<body>
    <?php // Sidebar toggle button ?>
    <button id="toggle-btn"><i class="fas fa-bars"></i></button>

    <div id="sidebar" class="hide">
        <div class="offcanvas-body">
            <a class="iconsonbar button text-muted" href="timesheet.php">
                <i class="con fas fa-stopwatch p-2"></i>Timesheet
            </a>
            <a class="iconsonbar button text-muted" href="search.php">
                <i class="con fas fa-search p-2"></i>Search
            </a>
            <a class="iconsonbar button text-muted" href="#" id="sidebar-add-task">
                <i class="con fas fa-plus p-2"></i>Add Task
            </a>
            <p class="fw-semibold mtdd">My Tasks</p>
            <a class="text-start iconsonbar2" href="task.php">
                <i class="fas fa-check icon-gap"></i>All Tasks
            </a>
            <a class="text-start iconsonbar2" href="#">
                <i class="fas fa-calendar icon-gap"></i>Today
            </a>
            <hr>
            <a class="text-start iconsonbar2" href="help.php">
                <i class="fas fa-question icon-gap"></i>Help & Tutorials
            </a>
        </div>
    </div>

    <div id="main-content">
        <header>
            <div class="logo">Organizr</div>
            <div class="right-header">
                <div class="btn-group">
                    <!-- Export Dropdown -->
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-download"></i>Export
                    </button>
                    <ul class="dropdown-menu X">
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-print"></i>Print todo-list</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-file-pdf"></i>Save as PDF</a></li>
                    </ul>

                    <!-- View Selector -->
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-hand-pointer"></i>Select
                    </button>
                    <ul class="dropdown-menu Y">
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-list"></i>List View</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-table"></i>Tiles View</a></li>
                    </ul>

                    <!-- Filter -->
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-arrow-down-short-wide"></i>Filtering
                    </button>
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
                            <label><input type="checkbox" class="checkbox"><i class="fa-solid fa-flag-checkered"></i>
                                Overdue</label>
                            <label><input type="checkbox" class="checkbox"><i class="fa-solid fa-flag-checkered"></i>Due
                                Today</label>
                            <label><input type="checkbox" class="checkbox"><i class="fa-solid fa-flag-checkered"></i>Due
                                Next
                                <input type="number" min="0" value="1">days</label>
                        </div>
                    </ul>

                    <!-- LOGIN PAGE START -->
                    <button class="btn btn-outline-success me-2" id="open-login-modal" type="button">
                        <i class="fa-regular fa-circle-user"></i> Sign In
                    </button>

                    <div id="login-modal" class="modal-overlay" style="display:none;">
                        <div class="modal-content">
                            <span class="close-modal" id="close-login-modal">&times;</span>
                            <h1>Organizr</h1>
                            <h4>Sign in to Organizr</h4>
                            <form id="login-form" action="login.php" method="POST">
                                <label for="login-email">Email</label>
                                <input type="email" id="login-email" name="email" placeholder="Enter your email"
                                    required>
                                <label for="login-password">Password</label>
                                <input type="password" id="login-password" name="password"
                                    placeholder="Enter your password" required>
                                <button type="submit" class="add-btn"
                                    style="width:100%;margin-bottom: 1.5rem;">Login</button>
                                <label class="remember-me">
                                    <input type="checkbox" id="remember-me">Remember me</label>
                                <p class="forgot-password"><a href="#">Forgot Password?</a></p>
                                <p class="create-account">Don't have an account? <a href="#">Create Account</a></p>
                            </form>
                        </div>
                    </div>
                    <!-- LOGIN PAGE END -->

                    <!-- REGISTRATION PAGE START -->
                    <div id="create-account-modal" class="modal-overlay" style="display:none;">
                        <div class="modal-content">
                            <span class="close-modal" id="close-create-account-modal">&times;</span>
                            <h1>Organizr</h1>
                            <h4>Create a new account</h4>
                            <form id="register-form" action="register.php" method="POST">
                                <label for="register-name">Full Name</label>
                                <input type="text" id="register-name" name="name" placeholder="Enter your full name"
                                    required>
                                <label for="register-email">Email</label>
                                <input type="email" id="register-email" name="email" placeholder="Enter your email"
                                    required>
                                <label for="register-password">Password</label>
                                <input type="password" id="register-password" name="password"
                                    placeholder="Create a password" required>
                                <label for="register-confirm-password">Confirm Password</label>
                                <input type="password" id="register-confirm-password" name="confirm_password"
                                    placeholder="Confirm your password" required>
                                <button type="submit" class="add-btn" style="width:100%; margin-bottom: 1.5rem;">Create
                                    Account</button>
                                <p class="forgot-password">Already have an account? <a href="#"
                                        id="switch-to-login">Sign in</a></p>
                            </form>
                        </div>
                    </div>
                    <!-- REGISTRATION PAGE END -->
                </div>
            </div>
        </header>

        <!-- ADD TASK START -->
        <main>
            <div class="task-panel">
                <h2>All Tasks</h2>
                <button class="add-btn" id="show-task-form">+ Add Task</button>
                <form id="task-form" style="display:none;" action="add_task.php" method="POST">
                    <input type="text" id="title" name="title" placeholder="Title" required />
                    <textarea id="description" name="description" placeholder="Description" required></textarea>
                    <input type="date" id="due_date" name="due_date" required />
                    <button type="submit">Add Task</button>
                </form>
            </div>
        </main>
        <!-- ADD TASK END -->

        <footer class="tm-footer">
            <div class="tm-footer-main">
                <div class="tm-logo">
                    <i class="fa-solid fa-layer-group"></i> TaskManager
                </div>
                <div class="tm-col">
                    <h4>Home</h4><br>
                    <a href="#">About us</a><br>
                    <a href="#">Careers</a><br>
                    <a href="#">Blog</a>
                </div>
                <div class="tm-col">
                    <h4>Support</h4><br>
                    <a href="#">Help Center</a><br>
                    <a href="#">Contact</a><br>
                    <a href="#">Privacy Policy</a>
                </div>
            </div>
            <div class="tm-footer-bottom">
                <p>&copy; <?php echo date("Y"); ?> tm. All rights reserved.</p>
                <div class="tm-social">
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </script>
</body>

</html>