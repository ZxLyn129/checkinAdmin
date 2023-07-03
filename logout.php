<?php
    session_start();

    // Destroy the session data
    session_destroy();

    // Remove the "Remember Me" cookies
    setcookie("cemail", "", time() - 3600);
    setcookie("cpass", "", time() - 3600);
    setcookie("crem", "", time() - 3600);

    // Redirect the user to the login page or any other desired page
    header("Location: index.php");
    exit();
?>