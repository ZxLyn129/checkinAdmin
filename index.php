<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    require './PHPMailer/src/Exception.php';
    require './PHPMailer/src/PHPMailer.php';
    require './PHPMailer/src/SMTP.php';
    
    include 'dbconnect.php';
    
    if (isset($_POST["submit"])) {
        $email = $_POST["email"];
        $pass = $_POST["password"];
        $haspassword = sha1($_POST["password"]);
        $stmt = $conn->prepare("SELECT * FROM tbl_admin WHERE admin_email = '$email' AND admin_password = '$pass' OR admin_password = '$haspassword'");
        $stmt->execute();
        $number_of_rows = $stmt->rowCount();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        if ($number_of_rows  > 0) {
            foreach ($rows as $admin)
            {
                $admin_name = $admin['admin_name'];
                $admin_phone = $admin['admin_phone'];
            }
            session_start();
            $_SESSION["sessionid"] = session_id();
            $_SESSION["admin_email"] = $email;
            $_SESSION["admin_name"] = $admin_name;
            $_SESSION["admin_phone"] = $admin_phone;
            echo "<script>alert('Login Success');</script>";
            echo "<script> window.location.replace('home.php?adminName=$admin_name')</script>";
        }else{
             echo "<script>alert('Login Failed');</script>";
             echo "<script> window.location.replace('index.php')</script>";
        }
    }
    if (isset($_POST["reset"])) {
         $email = $_POST["email"];
         $resetotp = rand(10000,99999);
         sendMail($email,$resetotp);
    }
    
    function sendMail($email, $resetotp){
        $mail = new PHPMailer();//true
        $mail->isSMTP();                                                    
        $mail->Host       = 'mail.nwarz.com'; 
        $mail->Port       = 465;
        $mail->SMTPDebug = 0;  
        $mail->SMTPAuth   = true;                                           
        $mail->Username   = '_mainaccount@nwarz.com';
        $mail->Password   = '';                            //
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
     
         $from = "_mainaccount@nwarz.com";
        
        $to = $email;
         $mail->addAddress($to);
         $subject = 'Checkin Website - Reset password request';
        $message = "<h2>You have requested to reset your password</h2> <p>Please click on the following link to reset your password and using this $resetotp to verify. If your did not request for the reset. You can ignore this email<p>
        <p><button><a href ='http://nwarz.com/checkinAdmin/verifyotp.php?email=$email&otp=$resetotp'>Verify Here</a></button>";
        
       $mail->setFrom($from,"Check-in");
       $mail->addAddress($to);                                             //Add a recipient
        
       //Content
       $mail->isHTML(true);                                                //Set email format to HTML
       $mail->Subject = $subject;
       $mail->Body    = $message;
       if ($mail->send()) {
             echo '<script>alert("Email sent successfully. Check your email")</script>';
             echo "<script> window.location.replace('http://nwarz.com/checkinAdmin/verifyotp.php?email=$email&otp=$resetotp')</script>";
       } else {
             echo '<script>alert("Error sending email: ". $mail->ErrorInfo)</script>'  ;   }
     }
    
?>

<!DOCTYPE html>
<html>

<head>
    <title>Check-in Admin</title>
    <link rel="shortcut icon" type="image/jpeg" href="image/logo.png">
    <meta charset="UTF-8">
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
            position: relative;
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
        .terms-button {
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            padding: 5px 10px;
            background-color: #f1f1f1;
            color: blue;
            text-decoration: none;
            border-radius: 4px;
        }
        .terms-button:hover{
            color: darkblue;
            text-decoration: underline;
        }
        @media (max-width: 767px) {
            .terms-button {
                position: static;
                margin-top: 20px;
                width: 100%;
                text-align: center;
            }
        }
    </style>

</head>

<body>
    <script>window.addEventListener('load', autoLogin);</script>

    <div class="header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-2 text-center">
                    <img src="image/logo1.png" alt="Logo" class="header-logo">
                </div>
                <div class="col-10 text-end">
                    <a href="tnc.php" class="terms-button text-end">
                        <i class="fas fa-file-alt"></i> Terms &amp; Conditions
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:180px; margin-bottom:175px;">
         <div class="w3-row w3-card">
         <div class="w3-half w3-container" style="margin-top: 50px; margin-bottom: 50px; text-align: center;">
          <img class="w3-image w3-center w3-padding" style="width:100%; height:100%;object-fit:cover;" src="image/login.png">
        </div>
            <div class="w3-half w3-container" style="margin-top: 50px; margin-bottom:50px;">
               <h4 style="font-size: 40px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; margin-bottom:40px;">Login</h4>
               <form name="loginForm" class=""  action="index.php" method="post">
                  <p>
                     <label style="color: #004891;">
                     <b style="margin-top: 10px;">Email</b>
                     </label>
                     <input class="w3-input w3-border w3-round" name="email" type="email" id="idemail" required>
                  </p>
                  <p>
                     <label style="color: #004891;">
                     <b style="margin-top: 10px;">Password</b>
                     </label>
                     <div class="input-group">
                        <input class="w3-input w3-border w3-round" name="password" type="password" id="idpass" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('idpass', 'togglePassword')">
                        <i class="far fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                        </button>
                    </div>
                  </p>
                  <p>
                     <input class="w3-check" style="margin-top: 10px;" type="checkbox" id="idremember" name="remember" onclick="rememberMee()">
                     <label>Remember Me</label>
                  </p>
                  <p>
                     <button class="button w3-btn w3-round w3-block" style="margin-top: 30px;" name="submit" value="login">Login</button>
                  </p>
               </form>

               <p style="margin-top: 45px; font-size:medium;">
               Forgot your password?  <a href="" style="text-decoration:none; color:blue; font-weight:500;" onclick="document.getElementById('id01').style.display='block';return false;"> Click here.</a>
               </p>

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

    <div id="id01" class="w3-modal">
      <div class="w3-modal-content" style="width:50%">
         <header class="w3-container" style="background-color: #F26B11; color:white; font-weight:500;">
            <span onclick="document.getElementById('id01').style.display='none'" 
               class="w3-button w3-display-topright">&times;</span>
            <h4 style="margin-top: 15px; margin-bottom:15px">Email to reset password</h4>
         </header>
         <div class="w3-container w3-padding">
            <p>We will send you an email to reset your password.</p>
            <form action="index.php" method="post">
               <label><b style="margin-top: 15px;">Email</b></label>
               <input class="w3-input w3-border w3-round" style="margin-top: 5px;" name="email" type="email" id="idemail" required>
               <p>
                  <button class="button w3-btn w3-round" style="margin-top: 20px;" name="reset">Submit</button>
               </p>
            </form>
         </div>
      </div>

</body>

</html>
