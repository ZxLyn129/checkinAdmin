<?php
include 'dbconnect.php';

if (isset($_GET['adminName'])){
    $adminName = $_GET['adminName'];
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT c.*, u.*, c.checkin_course
             FROM tbl_checkin c
             JOIN tbl_users u ON c.user_id = u.user_id
             WHERE c.checkin_id = :checkinid";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':checkinid', $id);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $conn = null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $checkin_id = $_POST['checkin_id'];
    $adminName = $_POST['admin_name'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];
    $group = $_POST['group'];

    // Update the record in tbl_checkin
    $query1 = "UPDATE tbl_checkin
               SET checkin_course = :course, checkin_group = :group
               WHERE checkin_id = :checkin_id";

    $stmt1 = $conn->prepare($query1);
    $stmt1->bindParam(':course', $course);
    $stmt1->bindParam(':group', $group);
    $stmt1->bindParam(':checkin_id', $checkin_id);
    $stmt1->execute();

    // Update the record in tbl_users
    $query2 = "UPDATE tbl_users
               SET user_name = :name, user_email = :email, user_phone = :phone
               WHERE user_id IN (SELECT user_id FROM tbl_checkin WHERE checkin_id = :checkin_id)";

    $stmt2 = $conn->prepare($query2);
    $stmt2->bindParam(':name', $name);
    $stmt2->bindParam(':email', $email);
    $stmt2->bindParam(':phone', $phone);
    $stmt2->bindParam(':checkin_id', $checkin_id);
    $stmt2->execute();

    // Redirect back to the edit page with a success message
    echo "<script>alert('Update Success');</script>";
    echo "<script> window.location.replace('https://nwarz.com/checkinAdmin/home.php?adminName=$adminName')</script>";
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

        h1 {
            margin-top: 120px;
            margin-bottom: 30px;
            font-weight: bold;
            font-family: cursive;
        }

        .content {
            flex: 1;
        }

        h1 {
            padding-left: 30px;
            padding-top: 30px;
            color: dimgray;
            font-weight: bold;
            font-family: cursive;
            text-decoration: underline;
        }

        .updatebtn {
            background-color: #ffa640;
            border-radius: 5px;
            border: none;
            margin-bottom: 20px;
            margin-top: 20px;
            margin-right: 35px;

        }

        .updatebtn:hover {
            background-color: red;
        }
        table{
            margin-left: 15px;
        }
        .table-label {
            font-size: larger;
            font-weight: bold;
            padding-right: 15px;
            text-align: right;
            padding: 0 10px;
            width: 10%;
        }

        .table-input {
            padding: 5px;
            width: 95%;
            box-sizing: border-box;
            margin-right: 30%;
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
        <div class="container">
            <div class="w3-card">
                <h1>Edit Information</h1>
                <form action="update.php?adminName=<?php echo "$adminName"; ?>" method="POST">
                    <input type="hidden" name="checkin_id" value="<?php echo $result['checkin_id']; ?>">
                    <input type="hidden" name="admin_name" value="<?php echo $adminName; ?>">

                    <!-- User Details -->
                    <table class="w3-table">
                        <tr>
                            <td class="table-label"><label for="name">Name:</label></td>
                            <td><input class="table-input" type="text" name="name" id="name" value="<?php echo $result['user_name']; ?>" required></td>
                        </tr>
                        <tr>
                            <td class="table-label"><label for="email">Email:</label></td>
                            <td><input class="table-input" type="email" name="email" id="email" value="<?php echo $result['user_email']; ?>" required></td>
                        </tr>
                        <tr>
                            <td class="table-label"><label for="phone">Phone:</label></td>
                            <td><input class="table-input" type="tel" name="phone" id="phone" value="<?php echo $result['user_phone']; ?>" required></td>
                        </tr>
                    </table>

                    <!-- Check-in Information -->
                    <table class="w3-table">
                        <tr>
                            <td class="table-label"><label for="course">Course:</label></td>
                            <td>
                                <select class="table-input" name="course" id="course" required>
                                    <option value="">Please choose your course</option>
                                    <option value="BPME1013 INTRODUCTION TO ENTREPRENEURSHIP " <?php if ($result['checkin_course'] == 'BPME1013 INTRODUCTION TO ENTREPRENEURSHIP ') echo 'selected'; ?>>BPME1013 INTRODUCTION TO ENTREPRENEURSHIP</option>
                                    <option value="BWFF1013 FUNDAMENTALS OF FINANCE " <?php if ($result['checkin_course'] == 'BWFF1013 FUNDAMENTALS OF FINANCE ') echo 'selected'; ?>>BWFF1013 FUNDAMENTALS OF FINANCE</option>
                                    <option value="MPU1013 PENGHAYATAN ETIKA DAN PERADABAN " <?php if ($result['checkin_course'] == 'MPU1013 PENGHAYATAN ETIKA DAN PERADABAN ') echo 'selected'; ?>>MPU1013 PENGHAYATAN ETIKA DAN PERADABAN</option>
                                    <option value="MPU1043 PHILOSOPHY AND CONTEMPORARY ISSUES " <?php if ($result['checkin_course'] == 'MPU1043 PHILOSOPHY AND CONTEMPORARY ISSUES ') echo 'selected'; ?>>MPU1043 PHILOSOPHY AND CONTEMPORARY ISSUES</option>
                                    <option value="SADN1033 MALAYSIAN NATIONHOOD STUDIES " <?php if ($result['checkin_course'] == 'SADN1033 MALAYSIAN NATIONHOOD STUDIES ') echo 'selected'; ?>>SADN1033 MALAYSIAN NATIONHOOD STUDIES</option>
                                    <option value="SGDN1043 SCIENCE OF THINKING AND ETHICS " <?php if ($result['checkin_course'] == 'SGDN1043 SCIENCE OF THINKING AND ETHICS ') echo 'selected'; ?>>SGDN1043 SCIENCE OF THINKING AND ETHICS</option>
                                    <option value="STIA1113 PROGRAMMING 1 " <?php if ($result['checkin_course'] == 'STIA1113 PROGRAMMING 1 ') echo 'selected'; ?>>STIA1113 PROGRAMMING 1</option>
                                    <option value="STIA1123 PROGRAMMING 2 " <?php if ($result['checkin_course'] == 'STIA1123 PROGRAMMING 2 ') echo 'selected'; ?>>STIA1123 PROGRAMMING 2</option>
                                    <option value="STIA2024 DATA STRUCTURES AND ALGORITHM ANALYSIS " <?php if ($result['checkin_course'] == 'STIA2024 DATA STRUCTURES AND ALGORITHM ANALYSIS ') echo 'selected'; ?>>STIA2024 DATA STRUCTURES AND ALGORITHM ANALYSIS</option>
                                    <option value="STID3014 DATABASE SYSTEM AND INFORMATION RETRIEVAL " <?php if ($result['checkin_course'] == 'STID3014 DATABASE SYSTEM AND INFORMATION RETRIEVAL ') echo 'selected'; ?>>STID3014 DATABASE SYSTEM AND INFORMATION RETRIEVAL</option>
                                    <option value="STID3024 SYSTEM ANALYSIS AND DESIGN " <?php if ($result['checkin_course'] == 'STID3024 SYSTEM ANALYSIS AND DESIGN ') echo 'selected'; ?>>STID3024 SYSTEM ANALYSIS AND DESIGN</option>
                                    <option value="STID3074 IT PROJECT MANAGEMENT " <?php if ($result['checkin_course'] == 'STID3074 IT PROJECT MANAGEMENT ') echo 'selected'; ?>>STID3074 IT PROJECT MANAGEMENT</option>
                                    <option value="STID3113 RESEARCH METHOD IN IT " <?php if ($result['checkin_course'] == 'STID3113 RESEARCH METHOD IN IT ') echo 'selected'; ?>>STID3113 RESEARCH METHOD IN IT</option>
                                    <option value="STIJ2024 BASIC NETWORKING " <?php if ($result['checkin_course'] == 'STIJ2024 BASIC NETWORKING ') echo 'selected'; ?>>STIJ2024 BASIC NETWORKING</option>
                                    <option value="STIK1014 COMPUTER SYSTEM ORGANIZATION " <?php if ($result['checkin_course'] == 'STIK1014 COMPUTER SYSTEM ORGANIZATION ') echo 'selected'; ?>>STIK1014 COMPUTER SYSTEM ORGANIZATION</option>
                                    <option value="STIK2044 OPERATING SYSTEM " <?php if ($result['checkin_course'] == 'STIK2044 OPERATING SYSTEM ') echo 'selected'; ?>>STIK2044 OPERATING SYSTEM</option>
                                    <option value="STIN1013 INTRODUCTION TO ARTIFICIAL INTELLIGENCE " <?php if ($result['checkin_course'] == 'STIN1013 INTRODUCTION TO ARTIFICIAL INTELLIGENCE ') echo 'selected'; ?>>STIN1013 INTRODUCTION TO ARTIFICIAL INTELLIGENCE</option>
                                    <option value="STIV2013 HUMAN COMPUTER INTERACTION " <?php if ($result['checkin_course'] == 'STIV2013 HUMAN COMPUTER INTERACTION ') echo 'selected'; ?>>STIV2013 HUMAN COMPUTER INTERACTION</option>
                                    <option value="STQM1203 MATHEMATICS FOR INFORMATION TECHNOLOGY " <?php if ($result['checkin_course'] == 'STQM1203 MATHEMATICS FOR INFORMATION TECHNOLOGY ') echo 'selected'; ?>>STQM1203 MATHEMATICS FOR INFORMATION TECHNOLOGY</option>
                                    <option value="STQM2103 DISCRETE STRUCTURE " <?php if ($result['checkin_course'] == 'STQM2103 DISCRETE STRUCTURE ') echo 'selected'; ?>>STQM2103 DISCRETE STRUCTURE</option>
                                    <option value="STQS1023 STATISTICS FOR INFORMATION TECHNOLOGY " <?php if ($result['checkin_course'] == 'STQS1023 STATISTICS FOR INFORMATION TECHNOLOGY ') echo 'selected'; ?>>STQS1023 STATISTICS FOR INFORMATION TECHNOLOGY</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                        <td class="table-label"><label for="group">Group:</label></td>
                        <td>
                            <select class="table-input" name="group" id="group" required>
                                <option value="">Please choose your group</option>
                                <option value="Group A" <?php if ($result['checkin_group'] == 'Group A') echo 'selected'; ?>>Group A</option>
                                <option value="Group B" <?php if ($result['checkin_group'] == 'Group B') echo 'selected'; ?>>Group B</option>
                                <option value="Group C" <?php if ($result['checkin_group'] == 'Group C') echo 'selected'; ?>>Group C</option>    
                            </select>
                                </td>
                            </tr>
                        </table>

                        <div class="w3-container text-end">
                            <button type="submit" class="button updatebtn">Update</button>
                        </div>
                    </form>
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

