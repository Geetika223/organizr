<?php
$insert = false;
if (isset($_POST["name"])) {
    $server = "localhost";
    $username = "root";
    $password = "";
    $con = mysqli_connect($server, $username, $password);
    if (!$con) {
        die("Connection to this database failed due to" . mysqli_connect_error());
    }
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $sql = "INSERT INTO `organizr`.`contact` (`Name`, `Email`, `Message`) VALUES ('$name', '$email', '$message')";
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
    <title>Contact Us | Organizr</title>
    <link rel="stylesheet" href="Style/Contact.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <header class="main-header">
        <div class="container">
            <h1>Contact Organizr</h1>
            <p>We'd love to hear from you!</p>
        </div>
    </header>

    <main class="container contact-section">
        <section class="contact-info">
            <h2><i class="fas fa-envelope"></i> Get in Touch</h2>
            <p>Have questions, feedback, or just want to say hello? Fill out the form or reach out through the channels
                below.</p>
            <ul>
                <li><i class="fas fa-map-marker-alt"></i> SKIT, Jaipur (302017)</li>
                <li><i class="fas fa-phone"></i> +91 123-456-7890</li>
                <li><i class="fas fa-envelope"></i> b240000@gmail.com</li>
            </ul>
        </section>

        <section class="contact-form">
            <h2><i class="fas fa-paper-plane"></i> Send a Message</h2>
            <form action="Contact.php" method="post">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
                <button type="submit" class="submit-btn">Send Message</button>

                <?php
                if ($insert == true) {
                    echo '<p> Thank You For Contacting Us.</p>';
                }
                ?>
            </form>
        </section>
    </main>

    <footer class="tm-footer">
        <div class="tm-footer-main container">
            <div class="tm-logo">
                <i class="fa-solid fa-layer-group"></i> Organizr
            </div>
            <div class="tm-col">
                <a href="Home.php">
                    <h4> Home</h4>
                </a>
                <a href="About_Us.html">About Us</a>
                <a href="Blog.html">Blog</a>
                <a href="Careers.html">Careers</a>
            </div>
            <div class="tm-col">
                <h4>Support</h4>
                <a href="Contact.php">Contact</a>
                <a href="Help_Centre.html">Help Center</a>
                <a href="Privacy_Policy.html">Privacy Policy</a>
            </div>
        </div>
        <div class="tm-footer-bottom container">
            <p>&copy; 2025 Organizr. All rights reserved.</p>
            <div class="tm-social">
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </footer>
</body>

</html>