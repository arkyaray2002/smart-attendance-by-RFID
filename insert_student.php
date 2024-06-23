<?php
// Database connection
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


// Retrieve form data
$student_name = $_POST['student_name'];
$student_rfid = $_POST['student_rfid'];
$student_sem = $_POST['student_sem'];

// Insert data into students table
$sql = "INSERT INTO students (name, rfid_uid, semester) VALUES ('$student_name', '$student_rfid', '$student_sem')";
if ($conn->query($sql) === TRUE) {
    // New record inserted successfully, generate JavaScript alert and return to previous page
    echo '<script type="text/javascript">';
    echo 'alert("New student record created successfully");';
    echo 'window.history.back();';
    echo '</script>';
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
