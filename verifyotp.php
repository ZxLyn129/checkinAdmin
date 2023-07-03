<?php
        error_reporting(0);
        include_once("dbconnect.php");
        $email = $_GET['email'];
        $resetotp = $_GET['otp'];            
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
    </head> 
    <body>
    <script type='text/javascript'>
        var email = "<?php echo $email; ?>";
        var otp = "<?php echo $resetotp; ?>";
        var entered = prompt('Please enter the OTP: '); 
            if(entered == otp){
                window.location.href = "https://nwarz.com/checkinAdmin/resetpass.php?email=" + email;
            }else{
                alert('OTP Uncorrect');
                window.location.replace('index.php');
            }
    </script>    
    </body>
</html>