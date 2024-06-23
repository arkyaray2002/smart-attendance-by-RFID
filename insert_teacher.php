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
$teacher_name = $_POST['teacher_name'];
$teacher_rfid = $_POST['teacher_rfid'];

// Insert data into teachers table
$sql = "INSERT INTO teachers (name, rfid_uid) VALUES ('$teacher_name', '$teacher_rfid')";
if ($conn->query($sql) === TRUE) {
    // New record inserted successfully, generate JavaScript alert and return to previous page
    echo '<script type="text/javascript">';
    echo 'alert("New teacher record created successfully");';
    echo 'window.history.back();';
    echo '</script>';
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
