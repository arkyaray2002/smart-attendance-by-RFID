<?php
include 'conn.php';

// Function to retrieve data from the database
function getData()
{
    global $conn;
    $stmt = $conn->query("SELECT * FROM `attendance`");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return json_encode($result);
}

echo getData();
?>
