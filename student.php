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
    <title>Student List</title>
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

        .container-button button{
            display: flex;
            justify-content: center;
            background: -webkit-linear-gradient(#03C04A,#234F1E);
            color: #fff;
            margin: auto;
            font-size: 25px;
            width: 250px;
            height: 65px;
            border-radius: 15px;
      }
        
    .filter-form input[type="submit"] {
   width: 50%;
  padding: 5px;  
  color: #ffffff;
  cursor: pointer;
  background-color: #03C04A;
}
  .container {
    background-color: rgba(255, 255, 255, 0.3);
    display: flex;
    justify-content: center;
}
.filter-form input[type="text"],
.filter-form input[type="datetime-local"] {
    width: 80%;
    padding: 12px;
    margin-bottom: 15px;
    border: none;
    border-radius: 5px;
    box-sizing: border-box;
    background-color: #99EDC390;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.filter-form input[type="number"] {
    width: 100px; 
    padding: 5px;
    margin-bottom: 15px;
    border: none;
    border-radius: 5px;
    box-sizing: border-box;
    background-color: #99EDC390;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
}
.popup-link{
  display:flex;
  flex-wrap:wrap;
}

.popup-link a{
    background: #333;
    color: #fff;
    padding: 10px 30px;
    border-radius: 5px;
    font-size:17px;
    cursor:pointer;
    margin:20px;
    text-decoration:none;
}

.popup-container {
    visibility: hidden;
    opacity: 0;
    transition: all 0.3s ease-in-out;
    transform: scale(1.3);
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(21, 17, 17, 0.61);
    display: flex;
    align-items: center;
}
.popup-content {
    background-color: #B0FC38;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
}
.popup-content p{
    font-size: 17px;
    padding: 10px;
    line-height: 20px;
}
.popup-content a.close{
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    background: none;
    padding: 0;
    margin: 0;
    text-decoration:none;
}

.popup-content a.close:hover{
  color:#f00;
}

.popup-content span:hover,
.popup-content span:focus {
    color: #B0FC38;
    text-decoration: none;
    cursor: pointer;
}

.popup-container:target{
  visibility: visible;
  opacity: 1;
  transform: scale(1);
}

.popup-container h3{
  margin:10px;
}

.popup-style{
  transform: skewY(180deg);
   transition: all 0.7s ease-in-out;
}

.popup-style:target{
 transform: skewY(0deg);

 }
    </style>
</head>
<body>

<a href="#popup7"><div class="container-button">
    <button><b>Below Attendance Calculator</b></button>
</div></a>

<br><br>

<div id="popup7" class="popup-container popup-style">
  <div class="popup-content">
    <a href="#" class="close">&times;</a>
    <div id="filterContainer" class="container">
        <div class="filter-form">
            <h2>Apply Filter</h2>
            <form id="filterForm" method="post" action="below_attendance.php">
                    Enter Total Days : <input type="number" id="Total_Class" name="Total_Class" placeholder="Total Class" required><br>
                    Enter Minimum Attendance (in %) : <input type="number" id="percentage" name="percentage" placeholder="Needed   %" required><br>
                <input type="submit" value="Filter" onclick="validateForm()">
            </form>
        </div>
    </div>
  </div>
</div>


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
        $sql = "SELECT * FROM students"; // Change "your_table_name" to your actual table name
        $result = $conn->query($sql);
    ?>

    <table>
    <thead>
      <tr>
        <th colspan="6" style="background:#6e2c03">Students Records</th>
      </tr>
    </thead>
        <tr>
            <th>Roll No.</th>
            <th>Name</th>
            <th>RFID UID</th>
            <th>Sem</th>
            <th>Attendance Count</th>
            <th>Action</th>
        </tr>
    <?php
        if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            $rfid_uid = $row["rfid_uid"];
            // Fetch attendance count for current RFID UID
            $attendance_query = "SELECT COUNT(*) AS attendance_count FROM attendance WHERE rfid_uid = '$rfid_uid'";
            $attendance_result = $conn->query($attendance_query);
            $attendance_row = $attendance_result->fetch_assoc();
            $attendance_count = $attendance_row["attendance_count"];

            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["rfid_uid"] . "</td>";
            echo "<td>" . $row["semester"] . "</td>";
            echo "<td>" . $attendance_count . "</td>"; // Display attendance count
            echo "<td><form action='delete_student.php' method='post'><input type='hidden' name='student_id' value='" . $row["id"] . "'><button type='submit' name='delete'><i class='fa fa-trash-o'></i></button></form></td>";
            echo "</tr>";
        }
        } else {
            echo "0 results";
        }

    ?>
    </table>
</body>
</html>