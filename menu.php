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
    <title>Menu</title>
    <link rel="icon" href="./images/attendance-icon.png" />
    <link rel="stylesheet" href="./menu.css">
    <style>
        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: linear-gradient(#03C04A,#234F1E);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 500px;
            margin-right: 700px;
        }

        .logout-button:hover {
          background: linear-gradient(#234F1E, #03C04A);
          cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="title">
        <h1>Student Smart Attendance System</h1>
    </div>

    <button class="logout-button" onclick="logout()"><b>Logout</b></button>

    <div class="card1" onclick="redirectToStudent()">
        <img src="./images/student.png" alt="student_card" height="10px">
        <h3>Student List</h3>
        <p>All the students, who've taken admission in the school, are listed here</p>
    </div>
    
    <div class="card2" onclick="redirectToTeacher()">
        <img src="./images/teacher.png" alt="teacher_card">
        <h3>Teacher List</h3>
        <p>All the teachers, who teach here in the school, are listed here</p>
    </div>
    
    <div class="card3" onclick="redirectToattendance()">
        <img src="./images/attendance.png" alt="attendance_card">
        <h3>Attendance</h3>
        <p>All the teachers and students, who are present in the school today, will be appeared in this tab.</p>
    </div>
    
    <div class="card4" onclick="redirectToEnroll()">
        <img src="./images/enroll_new.png" alt="teacher_card">
        <h3>Enroll New<br>Teacher/Student</h3>
        <p>Click here to enroll new Teacher/Student</p>
    </div>

    <script>
        function redirectToStudent() {
            window.location.href = './student.php';
        }

        function redirectToTeacher() {
            window.location.href = './teacher.php';
        }

        function redirectToattendance() {
            window.location.href = './attendance.php';
        }

        function redirectToEnroll() {
            window.location.href = './enrollment.php';
        }

        function logout() {
            window.location.href = './logout.php';
        }
    </script>
</body>
</html>
