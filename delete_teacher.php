<?php

if(isset($_POST['delete'])){
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

        // Get student ID from the form
    $student_id = $_POST['student_id'];

    // Prepare and execute the delete query
    $sql = "DELETE FROM teachers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    
    if ($stmt->execute()) {
        // Redirect back to the previous page after deletion
        header("Location: teacher.php");
        exit();
    } else {
        // Handle error
        echo "Error: " . $conn->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to an error page if accessed directly without form submission
    header("Location: error_page.php");
    exit();
}
?>
?>