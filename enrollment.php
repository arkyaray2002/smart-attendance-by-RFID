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
    <title>Insert Data Form</title>
    <link rel="icon" href="./images/attendance-icon.png" />
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
        .title{
            width:100%;
            text-align: center;
        }
        .title h1{
            font-size:50px;
            background: -webkit-linear-gradient(#03C04A,#234F1E);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <div class="title">
        <h1>Insert Data</h1>
    </div>

    <table>
    <!-- Form to insert data into the students table -->
    <th colspan="2">Insert Data into Students Table</th>
    <form action="insert_student.php" method="post">
        <tr><td><label for="student_name">Student Name:</label></td>
        <td><input type="text" id="student_name" name="student_name" required></td></tr>
        <tr><td><label for="student_rfid">Student RFID UID:</label><br></td>
        <td><input type="text" id="student_rfid" name="student_rfid" required></td></tr>
        <tr><td><label for="student_sem">Student Semester:</label></td>
        <td><select id="student_sem" name="student_sem">
                <option value="1">1st</option>
                <option value="2">2nd</option>
                <option value="3">3rd</option>
                <option value="4">4th</option>
                <option value="5">5th</option>
                <option value="6">6th</option>
            </select></td></tr>
        <tr><td colspan="2"><input type="submit" value="Insert as Student"></td></tr>
    </form>
    </table>

<br><br><br>

    <table>
    <!-- Form to insert data into the teachers table -->
    <th colspan="2">Insert Data into Teachers Table</th>
    <form action="insert_teacher.php" method="post">
        <tr><td><label for="teacher_name">Teacher Name:</label></td>
        <td><input type="text" id="teacher_name" name="teacher_name" required></td></tr>
        <tr><td><label for="teacher_rfid">Teacher RFID UID:</label></td>
        <td><input type="text" id="teacher_rfid" name="teacher_rfid" required></td></tr>
        <tr><td colspan="2"><input type="submit" value="Insert as Teacher"></td></tr>
    </form>
    </table>
</body>
</html>