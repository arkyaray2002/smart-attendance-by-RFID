<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher List</title>
    <link rel="icon" href="./images/attendance-icon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
        body {
            background-color: #B0FC38;
            align-items: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 50%;
            margin: auto;
        }
        th, td {
            text-align: center;
            padding: 8px;
        }
        tr:nth-child(even){
            background-color: #52e322;
        }
        tr:nth-child(odd){
            background-color: #3ca619;
        }
        th {
            background-color: #422626;
            color: white;
        }
        button{
            background: red;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: white;
            color: red;
        }
    </style>
</head>
<body>
    <?php
        // Connect to MySQL database
        $servername = "localhost"; 
        $username = "root";
        $password = "";
        $dbname = "smartattendancebyrfid";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch data from the table
        $sql = "SELECT * FROM teachers"; // Change "your_table_name" to your actual table name
        $result = $conn->query($sql);
    ?>

    <table>
    <thead>
      <tr>
        <th colspan="5" style="background:#6e2c03">Teachers Records</th>
      </tr>
    </thead>
        <tr>
            <th>Sl. No.</th>
            <th>Name</th>
            <th>RFID UID</th>
            <th>Action</th>
        </tr>
    <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["rfid_uid"] . "</td>";
                echo "<td><form action='delete_teacher.php' method='post'><input type='hidden' name='student_id' value='" . $row["id"] . "'><button type='submit' name='delete'><i class='fa fa-trash-o'></i></button></form></td>";
                echo "</tr>";
            }
        } else {
            echo "0 results";
        }

    ?>
    </table>
</body>
</html>