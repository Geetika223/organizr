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

// Variables to hold messages
$login_error = null;
$registration_error = null;
$registration_success = false;

// Check for session messages on page load
if (isset($_SESSION['login_error'])) {
    $login_error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
if (isset($_SESSION['registration_error'])) {
    $registration_error = $_SESSION['registration_error'];
    unset($_SESSION['registration_error']);
}
if (isset($_SESSION['registration_success'])) {
    $registration_success = $_SESSION['registration_success'];
    unset($_SESSION['registration_success']);
}

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

    // Handle login form submission
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

                header("Location: Home.php");
                exit;
            } else {
                $_SESSION['login_error'] = "Invalid email or password.";
            }
        } else {
            $_SESSION['login_error'] = "Invalid email or password.";
        }
        mysqli_stmt_close($stmt_login);
        header("Location: Index.php");
        exit;
    }

    // Handle registration form submission
    if (isset($_POST['register_email'])) {
        $full_name = trim($_POST['register_name']);
        $email = trim($_POST['register_email']);
        $password = trim($_POST['register_password']);
        $confirm_password = trim($_POST['register_confirm_password']);

        if ($password !== $confirm_password) {
            $_SESSION['registration_error'] = "Passwords do not match.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $check_sql = "SELECT id FROM users WHERE email = ?";
            $stmt_check = mysqli_prepare($con, $check_sql);
            mysqli_stmt_bind_param($stmt_check, "s", $email);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                $_SESSION['registration_error'] = "An account with this email already exists.";
            } else {
                $register_sql = "INSERT INTO `users` (`full_name`, `email`, `password`) VALUES (?, ?, ?)";
                $stmt_register = mysqli_prepare($con, $register_sql);
                mysqli_stmt_bind_param($stmt_register, "sss", $full_name, $email, $hashed_password);

                if (mysqli_stmt_execute($stmt_register)) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_id'] = mysqli_insert_id($con);
                    $_SESSION['user_name'] = $full_name;
                    $_SESSION['registration_success'] = true;

                    header("Location: Home.php");
                    exit;
                } else {
                    $_SESSION['registration_error'] = "ERROR: " . mysqli_error($con);
                }
                mysqli_stmt_close($stmt_register);
            }
            mysqli_stmt_close($stmt_check);
        }
        header("Location: Index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="Style/Index.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="logo">
            <i class="fa-solid fa-layer-group"></i> Organizr
        </div>
    </header>

    <div class="container">
        <div class="box">
            <img src="Images/Index.png" class="img1" alt="Login Illustration" />
        </div>
        <div class="box2">
            <h1>Welcome!</h1>
            <p>Manage your tasks with Organizr</p>
            <div class="btn-group">
                <button id="open-login-modal" class="btn login-btn">Log In to Dashboard</button>
                <div id="login-modal" class="modal-overlay" style="display:none;">
                    <div class="modal-content">
                        <span class="close-modal" id="close-login-modal">&times;</span>
                        <h1>Organizr</h1>
                        <h4>Sign in to Organizr</h4>
                        <?php if ($login_error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($login_error) ?></div>
                        <?php endif; ?>
                        <form id="login-form" action="Index.php" method="post">
                            <label for="login_email">Email</label>
                            <input type="email" id="login_email" name="login_email" placeholder="Enter your email" required>
                            <label for="login_password">Password</label>
                            <input type="password" id="login_password" name="login_password" placeholder="Enter your password" required>
                            <button type="submit" class="add-btn" style="width:100%;margin-bottom: 1.5rem;">Login</button>
                            <p style="text-align: center;" class="create-account">Don't have an account? <a href="#" id="switch-to-register">Create Account</a></p>
                        </form>
                    </div>
                </div>
                <div id="create-account-modal" class="modal-overlay" style="display:none;">
                    <div class="modal-content">
                        <span class="close-modal" id="close-create-account-modal">&times;</span>
                        <h1>Organizr</h1>
                        <h4>Create a new account</h4>
                        <?php if ($registration_error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($registration_error) ?></div>
                        <?php endif; ?>
                        <?php if ($registration_success): ?>
                            <div class="alert alert-success">Registration successful! You can now log in.</div>
                        <?php endif; ?>
                        <form id="register-form" action="Index.php" method="post">
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
                <a href="Home.php" class="btn demo-btn">Take Free Demo</a>
            </div>
        </div>
    </div>
    <script>
        const loginModal = document.getElementById("login-modal");
        const openLoginModalBtn = document.getElementById("open-login-modal");
        const closeLoginModalBtn = document.getElementById("close-login-modal");
        const createAccountModal = document.getElementById("create-account-modal");
        const closeCreateAccountModalBtn = document.getElementById("close-create-account-modal");
        const switchToRegisterLink = document.getElementById("switch-to-register");
        const switchToLoginLink = document.getElementById("switch-to-login");

        function disableBodyScroll() {
            document.body.style.overflow = 'hidden';
        }

        function enableBodyScroll() {
            document.body.style.overflow = 'auto';
        }

        // Event listeners for modals
        openLoginModalBtn.addEventListener("click", () => {
            loginModal.style.display = "flex";
            disableBodyScroll();
        });

        closeLoginModalBtn.addEventListener("click", () => {
            loginModal.style.display = "none";
            enableBodyScroll();
        });

        closeCreateAccountModalBtn.addEventListener("click", () => {
            createAccountModal.style.display = "none";
            enableBodyScroll();
        });

        switchToRegisterLink.addEventListener("click", (e) => {
            e.preventDefault();
            loginModal.style.display = "none";
            createAccountModal.style.display = "flex";
        });

        switchToLoginLink.addEventListener("click", (e) => {
            e.preventDefault();
            createAccountModal.style.display = "none";
            loginModal.style.display = "flex";
        });

        window.addEventListener("click", (event) => {
            if (event.target === loginModal) {
                loginModal.style.display = "none";
                enableBodyScroll();
            }
            if (event.target === createAccountModal) {
                createAccountModal.style.display = "none";
                enableBodyScroll();
            }
        });

        // PHP logic to open the correct modal on page load if an error exists
        <?php if ($login_error): ?>
            loginModal.style.display = 'flex';
            disableBodyScroll();
        <?php endif; ?>
        
        <?php if ($registration_error || $registration_success): ?>
            createAccountModal.style.display = 'flex';
            disableBodyScroll();
        <?php endif; ?>
    </script>
    <?php close_resources(null, $con); ?>
</body>

</html>