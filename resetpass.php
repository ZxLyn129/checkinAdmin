<?php

isset($_GET['email']) ? $email = $_GET['email'] : $email = '';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Check-in Admin</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/jpeg" href="image/logo.png">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>

    <style>
        body {
            background-image: url(image/image.png);
            background-attachment: fixed;
            background-size: cover;
            -moz-background-size: cover;
            -webkit-background-size: cover;
            -o-background-size: cover;
            -ms-background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .w3-card {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            -moz-border-radius: 10px;
            -webkit-border-radius: 10px;
            -o-border-radius: 10px;
            -ms-border-radius: 10px;
        }

        .header {
            height: 85px;
            background-color: whitesmoke;
            align-items: center;
            margin-bottom: 0;
            margin-top: 0 !important;
            top: 0 !important;
            padding-top: 0 !important;
            position: absolute;
            width: 100%;
        }

        .header-logo {
            height: 70px;
            width: auto;
            margin: 5px auto;
            margin-bottom: 5px;
            margin-bottom: 10px;
        }

        .footer {
            background-color: whitesmoke;
            height: 65px;
            margin-top: 40px;
            text-align: center;
            color: black;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-2 text-center">
                    <img src="image/logo1.png" alt="Logo" class="header-logo">
                </div>
            </div>
        </div>
    </div>
    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:275px; margin-bottom:200px;">
        <div class="w3-row w3-card">
            <div class="w3-half w3-container" >
                <img class="w3-image w3-padding" style="width:100%; height:100%; display:flex; justify-content: center; align-items: center; margin-top:20px; margin-bottom:20px" src="image/reset.png">
            </div>

            <div class="w3-half w3-container" style="margin-top: 50px; margin-bottom:50px;">
                <h4 style="font-size: 40px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; margin-bottom:40px;">Reset Password</h4>
                <form name="registerPassword" class="" action="resetpass.php?email=<?php echo $email; ?>" method="post" id="resetForm">
                    <p>
                        <label style="color: #004891;">
                            <b style="margin-top: 10px;">Password</b>
                        </label>
                    <div class="input-group">
                        <input class="w3-input w3-border w3-round" name="password" type="password" id="idpass" value="<?php echo isset($_SESSION['password']) ? $_SESSION['password'] : ''; ?>" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('idpass', 'togglePassword')">
                            <i class="far fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                        </button>
                    </div>
                    </p>
                    <p>
                        <label style="color: #004891;">
                            <b style="margin-top: 10px;">Confirm Password</b>
                        </label>
                    <div class="input-group">
                        <input class="w3-input w3-border w3-round" name="passwordb" type="password" id="idpassb" value="<?php echo isset($_SESSION['passwordb']) ? $_SESSION['passwordb'] : ''; ?>" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('idpassb', 'togglePasswordb')">
                            <i class="far fa-eye" id="togglePasswordb" style="cursor: pointer;"></i>
                        </button>
                    </div>
                    </p>
                    <p>
                        <button class="button w3-btn w3-round w3-block" style="margin-top: 30px;" name="reset" value="reset">Reset</button>
                </form>
                <script>
                    // Get the email parameter from the URL
                    var email = "<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>";

                    // Set the form action with the email parameter
                    document.getElementById("resetForm").action = "resetpass.php?email=<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>";
                </script>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, eyeIconId) {
            var input = document.getElementById(inputId);
            var eyeIcon = document.getElementById(eyeIconId);

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

    <div class="footer">
        <hr style="height: 2px; background-color: black;">
        <p> &copy; <?php echo date('Y'); ?> | Check-in System&reg;. All rights reserved. </p>
    </div>
</body>

</html>

<?php
include_once("dbconnect.php");

if (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    $email = '';
}

if (isset($_POST['reset'])) {
    $password = $_POST['password'];
    $passwordb = $_POST['passwordb'];

    if ($password == $passwordb) {
        $haspassword = sha1($password);
        if (!empty($email)) {
            $sqlupdate = "UPDATE `tbl_admin` SET admin_password = '$haspassword' WHERE admin_email = '$email'";
            $stmt = $conn->prepare($sqlupdate);
            $stmt->execute();
            $number_of_rows = $stmt->rowCount();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();
            if ($number_of_rows  > 0) {
                echo "<script>alert('Reset Success')</script>";
                echo "<script>window.location.replace('index.php')</script>";
            } else {
                echo "<script>alert('Failed to update password. The password is the same as the previously set password.')</script>";
            }
        } else {
            echo "<script>alert('The email is empty')</script>";
            $password = $_POST["password"];
            $passwordb = $_POST["passwordb"];
            echo "<script>window.location.href = \"http://nwarz.com//checkinAdmin/resetpass.php?email=$email\"</script>";
        }
    } else {
        echo "<script>alert('Please make sure the password is the same as the confirm password.')</script>";
        $password = $_POST["password"];
        $passwordb = $_POST["passwordb"];

        $_SESSION['password'] = $password;
        $_SESSION['passwordb'] = $passwordb;

        echo "<script>window.location.href = \"https://nwarz.com//checkinadmin/resetpass.php?email=$email\"</script>";
    }
}
?>
