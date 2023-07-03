<?php
    include 'dbconnect.php';

    if (isset($_GET['adminName'])){
        $adminName = $_GET['adminName'];
    }

    try{
        $query = "SELECT c.*, u.*
            FROM tbl_checkin c
            JOIN tbl_users u ON c.user_id = u.user_id";

        $stmt = $conn->query($query);
            
        if (isset($_GET['search'])) {
            $search = $_GET['search'];

            if ($search != ""){
            
                $query1= " SELECT c.*, u.*
                    FROM tbl_checkin c
                    JOIN tbl_users u ON c.user_id = u.user_id 
                    WHERE c.user_id LIKE :search
                            OR u.user_name LIKE :search
                            OR c.checkin_course LIKE :search
                            OR c.checkin_group LIKE :search
                            OR c.checkin_location LIKE :search
                            OR c.checkin_state LIKE :search
                            OR c.checkin_lat LIKE :search
                            OR c.checkin_long LIKE :search
                            OR c.checkin_date LIKE :search";

                $stmt = $conn->prepare($query1);
                $stmt->bindValue(':search', '%' . $search . '%');
                $stmt->execute();
            }else{
                echo "<script>alert('Please enter the search keyword.');</script>";
            }
        }

        $rows = array();
        if ($stmt->rowCount() > 0) {
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            echo '<div class="alert alert-danger"><em>No check-in data available.</em></div>';
        }
    }catch (PDOException $e) {
        echo "Query failed: " . $e->getMessage();
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
        h1{
            margin-top: 120px;
            margin-bottom: 30px;
            font-weight: bold;
            font-family: cursive;
        }
        .content {
            flex: 1;
        }
        .table {
            background-color: white;
            border-collapse: collapse; 
            width: 100%;
        }
        .table th, .table td {
            border: 2px solid darkcyan; 
            padding: 6px;
        }
        th{
            text-align: center; 
        }
        .table thead tr th{
            background-color: gray;
            color: white;
        }
        .date {
            white-space: nowrap;
        }
        .table td a:not(:last-child) {
            margin-right: 8px;
        }
        .table td a {
            color: #ffa640;
            margin: 0 4px;
        }

        .table td a:hover {
            color: red;
        }
        .delete-entry-modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .delete-entry-modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 500px;
            text-align: center;
        }

        .delete-entry-modal-content h2 {
            margin-top: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .delete-entry-modal-content p {
            margin-bottom: 20px;
        }

        .delete-entry-modal-content button.btnDelete,
        .delete-entry-modal-content button.btnCancel {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .delete-entry-modal-content button.btnDelete {
            background-color:#F03D4F ;
            color: white;
            border: none;
        }

        .delete-entry-modal-content button.btnDelete:hover {
            background-color: #EB1B30;
        }

        .delete-entry-modal-content button.btnCancel {
            background-color: #6c757d;
            color: white;
            border: none;
            margin-left: 10px;
        }

        .delete-entry-modal-content button.btnCancel:hover {
            background-color: #5a6268;
        }
        .action-buttons a {
            display: inline-block;
            margin-right: 3px;
        }

        .action-buttons a:last-child {
            margin-right: 0;
        }
        .welcome-user {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            text-align: center;
            padding: 10px;
        }
        
    </style>

</head>

<body>
<div class="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-5 text-center">
                <a href="home.php?adminName=<?php echo "$adminName"; ?>" class="header-logo-link"><img src="image/logo1.png" alt="Logo" class="header-logo"></a>
            </div>
            <div class="col-5">
                <div class="welcome-user">
                    Welcome, <?php echo "$adminName"; ?> !
                </div>
            </div>
            <div class="col-2 text-end">
                <div class="logout-box">
                    <a href="logout.php" class="btn btn-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="content">
        <div class="container mt-5" >
            <h1>Check-in Details</h1>

            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-6" style="margin-right: 105px;">
                    <form method="GET" action="home.php">
                        <div class="input-group" style="margin-bottom: 10px;">
                            <input type="text" class="form-control" name="search" placeholder="Searching...">
                            <input type="hidden" name="adminName" value="<?php echo $adminName; ?>">
                            <button type="submit" class="button btn" id="search"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>

                <div class="col-md-5 text-end">
                    <form method="POST" action="report.php?adminName=<?php echo "$adminName"; ?>" style="display: inline;">
                        <div class="input-group">
                            <label for="start_date" class="sr-only">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" required>
                            <span class="input-group-text">to</span>
                            <label for="end_date" class="sr-only">End Date</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" required>
                            <button type="submit" class="button btn" id="report">
                                <i class="fas fa-file-pdf"></i> Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (count($rows) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Course</th>
                            <th>Group</th>
                            <th>Location</th>
                            <th>State</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?php echo $row['user_name']; ?></td>
                                <td><?php echo $row['checkin_course']; ?></td>
                                <td><?php echo $row['checkin_group']; ?></td>
                                <td><?php echo $row['checkin_location']; ?></td>
                                <td><?php echo $row['checkin_state']; ?></td>
                                <td><?php echo $row['checkin_lat']; ?></td>
                                <td><?php echo $row['checkin_long']; ?></td>
                                <td class="date"><?php echo date('Y-m-d H:i:s', strtotime($row['checkin_date'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="details.php?id=<?php echo $row['checkin_id']; ?>&&adminName=<?php echo "$adminName"; ?>" title="View Record" data-toggle="tooltip">
                                            <span class="fas fa-eye"></span>
                                        </a>
                                        <a href="update.php?id=<?php echo $row['checkin_id']; ?>&&adminName=<?php echo "$adminName"; ?>" title="Update Record" data-toggle="tooltip">
                                            <span class="fas fa-pencil-alt"></span>
                                        </a>
                                        <a href="#" onclick="openDeleteModal(<?php echo $row['checkin_id']; ?>, '<?php echo $row['user_name']; ?>', '<?php echo $row['checkin_course']; ?>')" title="Delete Record" data-toggle="tooltip">
                                            <span class="fas fa-trash"></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-danger"><em>No check-in data available.</em></div>
            <?php endif; ?>
        </div>
    </div> 
    
    <div class="delete-entry-modal" id="deleteEntryModal">
        <div class="delete-entry-modal-content">
            <h2>Delete Check-in Details</h2>
            <p>Are you sure you want to delete the details for the user '<span id="deleteUserName"></span>' and the course '<span id="deleteCourse"></span>'?</p>
            <form method="POST" id="deleteEntryForm">
                <button class="btnDelete" type="submit" name="confirm" value="yes">Delete</button>
                <button class="btnCancel" type="button" onclick="closeDeleteModal()">Cancel</button>
            </form>
        </div>
    </div>
    <script>
        function openDeleteModal(entryId, userName, course) {
            const modal = document.getElementById("deleteEntryModal");
            const form = document.getElementById("deleteEntryForm");
            const deleteUserName = document.getElementById("deleteUserName");
            const deleteCourse = document.getElementById("deleteCourse");

            form.action = `delete.php?id=${entryId}&&adminName=<?php echo $adminName; ?>`;
            deleteUserName.textContent = userName;
            deleteCourse.textContent = course;

            modal.style.display = "block";

            // Disable search button
            const searchButton = document.getElementById("search");
            searchButton.disabled = true;

            // Disable generate report button
            const generateReportButton = document.getElementById("report");
            generateReportButton.disabled = true;
        }

        function closeDeleteModal() {
            const modal = document.getElementById("deleteEntryModal");
            modal.style.display = "none";

            // Enable search button
            const searchButton = document.getElementById("search");
            searchButton.disabled = false;

            // Enable generate report button
            const generateReportButton = document.getElementById("report");
            generateReportButton.disabled = false;
        }
    </script>
    
    <footer>
        <div class="footer">
            <hr style="height: 2px; background-color: black;">
            <p> &copy; <?php echo date('Y'); ?> | Check-in System&reg;. All rights reserved. </p>
        </div>
    </footer>

</body>

</html>