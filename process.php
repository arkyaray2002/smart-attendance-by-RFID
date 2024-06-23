<?php
include 'conn.php';
include 'dbwrite.php'; // Include dbwrite.php to access isTeacher() and isStudent() functions

// Function to retrieve teacher attendance records
function getTeacherAttendance()
{
    global $conn;
    $output = ''; // Initialize an empty string to hold the HTML output
    
    $logs = $conn->query("SELECT * FROM `attendance`");
    while ($r = $logs->fetch()) {
        if (isTeacher($r['rfid_uid'])) { // Check if the record belongs to a teacher
            $output .= "<tr>";
            $output .= "<td>".$r['rfid_uid']."</td>";
            $output .= "<td><b>".$r['name']."</b></td>";
            $output .= "<td>".$r['entry_time']."</td>";
            $output .= "<td>".$r['exit_time']."</td>";
            $output .= "</tr>";
        }
    }
    
    return $output;
}

// Function to retrieve student attendance records
function getStudentAttendance()
{
    global $conn;
    $output = ''; // Initialize an empty string to hold the HTML output
    
    $logs = $conn->query("SELECT * FROM `attendance`");
    while ($r = $logs->fetch()) {
        if (isStudent($r['rfid_uid'])) { // Check if the record belongs to a student
            $output .= "<tr>";
            $output .= "<td>".$r['rfid_uid']."</td>";
            $output .= "<td><b>".$r['name']."</b></td>";
            $output .= "<td>".$r['entry_time']."</td>";
            $output .= "<td>".$r['exit_time']."</td>";
            $output .= "</tr>";
        }
    }
    
    return $output;
}

// Return teacher and student attendance tables as strings
$teacherAttendanceTable = "<table>";
$teacherAttendanceTable .= "<thead><tr><th colspan='4' style='background:#6e2c03'>Teacher Attendance Records</th></tr>";
$teacherAttendanceTable .= "<tr><th>RFID UID</th><th>Name</th><th>Entry Time</th><th>Exit Time</th></tr></thead>";
$teacherAttendanceTable .= "<tbody>" . getTeacherAttendance() . "</tbody>";
$teacherAttendanceTable .= "</table>";

$studentAttendanceTable = "<table>";
$studentAttendanceTable .= "<thead><tr><th colspan='4' style='background:#6e2c03'>Student Attendance Records</th></tr>";
$studentAttendanceTable .= "<tr><th>RFID UID</th><th>Name</th><th>Entry Time</th><th>Exit Time</th></tr></thead>";
$studentAttendanceTable .= "<tbody>" . getStudentAttendance() . "</tbody>";
$studentAttendanceTable .= "</table>";

// Output teacher and student attendance tables
echo $teacherAttendanceTable;
echo $studentAttendanceTable;
?>
