<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

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

// Get filter inputs
$total_classes = isset($_POST['Total_Class']) ? (int)$_POST['Total_Class'] : 0;
$min_percentage = isset($_POST['percentage']) ? (int)$_POST['percentage'] : 0;

// Validate inputs
if ($total_classes <= 0 || $min_percentage < 0 || $min_percentage > 100) {
    echo "Invalid input. Please provide valid total classes and minimum attendance percentage.";
    exit();
}

// Fetch students and their attendance
$sql = "SELECT students.id, students.name, students.semester, students.rfid_uid, 
               (SELECT COUNT(*) FROM attendance WHERE attendance.rfid_uid = students.rfid_uid) AS attendance_count 
        FROM students";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Below Attendance Level</title>
    <style>
        body {
            background-color: #B0FC38;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
        }
        h1 {
            font-size: 50px;
            width: 100%;
            text-align: center;
            background: -webkit-linear-gradient(#03C04A, #234F1E);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        table {
            border-collapse: collapse;
            width: 70%;
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
        button {
                background: -webkit-linear-gradient(#03C04A,#234F1E);
                color: #fff;
                margin: auto;
                font-size: 25px;
                width: 250px;
                height: 40px;
                display: block;
                margin-top: 20px; 
                cursor: pointer;
                border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1 style="text-align:center">Students Below Attendance Level</h1>
    <table id="attendanceTable">
        <tr>
            <th>Name</th>
            <th>Semester</th>
            <th>Attendance Percentage</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $attendance_count = $row['attendance_count'];
                $attendance_percentage = ($attendance_count / $total_classes) * 100;

                if ($attendance_percentage < $min_percentage) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['semester'] . "</td>";
                    echo "<td>" . number_format($attendance_percentage, 2) . "%</td>";
                    echo "</tr>";
                }
            }
        } else {
            echo "<tr><td colspan='3'>No students found</td></tr>";
        }
        ?>
    </table>

    
    <button onclick="exportToExcel()">Download as Excel</button>

    <!-- Include the SheetJS library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script>
        function exportToExcel() {
            var table = document.getElementById("attendanceTable");
            var wb = XLSX.utils.table_to_book(table, {sheet: "Attendance"});

            // Adjust column widths
            var ws = wb.Sheets["Attendance"];
            var range = XLSX.utils.decode_range(ws['!ref']);
            ws['!cols'] = [];
            for (var C = range.s.c; C <= range.e.c; ++C) {
                if (C === 0) {
                    ws['!cols'][C] = {wch: 16}; // Name column width
                } else if (C === 1) {
                    ws['!cols'][C] = {wch: 10}; // Semester column width
                } else {
                    ws['!cols'][C] = {wch: 20}; // Attendance percentage column width
                }
            }

            var wbout = XLSX.write(wb, {bookType: 'xlsx', bookSST: true, type: 'binary'});
            function s2ab(s) {
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }
            var blob = new Blob([s2ab(wbout)], {type: 'application/octet-stream'});
            var fileName = 'below_attendance_records.xlsx';
            if (navigator.msSaveBlob) {
                // For IE and Edge
                navigator.msSaveBlob(blob, fileName);
            } else {
                var link = document.createElement('a');
                if (link.download !== undefined) {
                    // For other browsers
                    var url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', fileName);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
