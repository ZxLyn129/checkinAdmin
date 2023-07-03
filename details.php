<?php
    include 'dbconnect.php';

    if (isset($_GET['adminName'])){
        $adminName = $_GET['adminName'];
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $query = "SELECT c.*, u.*
            FROM tbl_checkin c
            JOIN tbl_users u ON c.user_id = u.user_id
            WHERE c.checkin_id = :checkinid";

        $stmt = $conn->prepare($query);
        $stmt ->bindParam( ':checkinid', $id);
        $stmt -> execute();

        $result = $stmt ->fetch(PDO::FETCH_ASSOC);

        $conn = null;
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
            background-image: url(image/image2.jpg);
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
        .header-logo-link {
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .header-logo-link:hover .header-logo {
            transform: scale(1.1);
        }

        .header-logo {
            height: 70px;
            width: auto;
            margin: 5px auto;
            margin-bottom: 5px;
            margin-bottom: 10px;
        }

        .header .logout-box {
            display: inline-block;
            background-color: #ffa640;
            padding: 6px 15px;
            border-radius: 5px;
        }

        .header .btn-link {
            color: white;
            font-size: 18px;
            text-decoration: none;
        }

        .header .logout-box:hover {
            background-color: red;
        }

        .footer {
            background-color: whitesmoke;
            height: 65px;
            margin-top: auto;
            text-align: center;
            color: black;
            width: 100%;
        }
        h2.user-details-heading{
            padding-left: 30px;
            padding-top: 30px;
            color: dimgray;
            font-weight: bold;
            font-family: cursive;
            text-decoration: underline;
        }
        h2.checkin-details-heading{
            padding-left: 30px;
            padding-top: 30px;
            color: dimgray;
            font-weight: bold;
            font-family: cursive;
            text-decoration: underline;
        }
        .user-details,
        .checkin-details {
            font-family: sans-serif;
            font-size: large;
            padding-left: 30px;
            padding-top: 10px;
            padding-bottom: 10px;
            line-height: 1.8;
        }

        .user-details .row,
        .checkin-details .row {
            margin-bottom: 10px;
        }

        .content {
            flex: 1;
        }
        .date {
            white-space: nowrap;
        }
        p{
            padding-left: 30px;
            font-size: larger;
            line-height: 1.8;
        }
    </style>

</head>

<body>
    <div class="header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-2 text-center">
                    <a href="home.php?adminName=<?php echo "$adminName"; ?>" class="header-logo-link"><img src="image/logo1.png" alt="Logo" class="header-logo"></a>
                </div>
                <div class="col-10 text-end">
                    <div class="logout-box">
                        <a href="logout.php" class="btn btn-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content">
        <div class="container" style="max-width: 1200px; margin-top: 120px; margin-bottom: 85px;">
            <div class="row">
                <div class="col">
                    <div class="w3-card">
                        <h2 class="user-details-heading">User Details</h2>
                        <div class="user-details">
                            <div class="row">
                                <div class="col-4"><strong>Name:</strong></div>
                                <div class="col-8"><?php echo $result['user_name']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-4"><strong>Email:</strong></div>
                                <div class="col-8"><?php echo $result['user_email']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-4"><strong>Phone:</strong></div>
                                <div class="col-8"><?php echo $result['user_phone']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-4"><strong>Date Registration:</strong></div>
                                <div class="col-8"><?php echo date('F j, Y g:i A', strtotime($result['user_datereg'])); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="w3-card">
                        <h2 class="checkin-details-heading">Check-in Information</h2>
                        <div class="checkin-details">
                            <div class="row">
                                <div class="col-4"><strong>Course:</strong></div>
                                <div class="col-8"><?php echo $result['checkin_course']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-4"><strong>Group:</strong></div>
                                <div class="col-8"><?php echo $result['checkin_group']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-4"><strong>Location:</strong></div>
                                <div class="col-8"><?php echo $result['checkin_location']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-4"><strong>State:</strong></div>
                                <div class="col-8"><?php echo $result['checkin_state']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-4"><strong>Latitude:</strong></div>
                                <div class="col-8"><?php echo $result['checkin_lat']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-4"><strong>Longitude:</strong></div>
                                <div class="col-8"><?php echo $result['checkin_long']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-4"><strong>Date and Time:</strong></div>
                                <div class="col-8"><?php echo date('F j, Y g:i A', strtotime($result['checkin_date'])); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <footer>
        <div class="footer">
            <hr style="height: 2px; background-color: black;">
            <p> &copy; <?php echo date('Y'); ?> | Check-in System&reg;. All rights reserved. </p>
        </div>
    </footer>

</body>

</html>