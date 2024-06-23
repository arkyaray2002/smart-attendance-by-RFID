<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtered Attendance Records</title>
    <link rel="icon" href="./images/attendance-icon.png" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.0/xlsx.full.min.js"></script>

    <style>
        body {
            background-color: #B0FC38;
            align-items: center;
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
            width: 50%;
            margin: auto;
            margin-bottom: 20px; /* Add margin between tables */
        }
        
        th, td {
            text-align: center;
            padding: 8px;
        }
        
        tr:nth-child(even) {
            background-color: #52e322;
        }
        
        tr:nth-child(odd) {
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
    <h1>Filtered Attendance Records</h1>

    <table id="attendanceTable">
        <thead>
            <tr>
                <th>RFID UID</th>
                <th>Name</th>
                <th>Entry Time</th>
                <th>Exit Time</th>
            </tr>
        </thead>
        <tbody>
        <?php
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

// Initialize an empty array to store conditions
$conditions = array();

// Check if each field has been posted and add it to the conditions array
if(isset($_POST['name']) && !empty($_POST['name'])) {
    $name = $_POST['name'];
    $conditions[] = "name = '$name'";
}

if(isset($_POST['student_sem']) && !empty($_POST['student_sem'])) {
    $student_sem = $_POST['student_sem'];
    $conditions[] = "name IN (SELECT name FROM students WHERE semester = ' $student_sem')";
}

// Check if entry and exit timings are posted
if(isset($_POST['entryTime']) && !empty($_POST['entryTime']) && isset($_POST['exitTime']) && !empty($_POST['exitTime'])) {
    $entryTime = $_POST['entryTime'];
    $exitTime = $_POST['exitTime'];
    $conditions[] = "entryTime >= '$entryTime' AND exitTime <= '$exitTime'";
}

// Construct SQL query with dynamic WHERE clause
$sql = "SELECT * FROM attendance";

if(!empty($conditions)) {
    $whereClause = implode(' AND ', $conditions);
    $sql .= " WHERE " . $whereClause;
}

// echo $sql;

// Execute the query
$result = $conn->query($sql);

$output = ''; 
// Check if any rows are returned
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $output .= "<tr>";
        $output .= "<td>".$row['rfid_uid']."</td>";
        $output .= "<td><b>".$row['name']."</b></td>";
        $output .= "<td>".$row['entry_time']."</td>";
        $output .= "<td>".$row['exit_time']."</td>";
        $output .= "</tr>";
    }
    
    echo $output;
} else {
    echo "<script> alert('No records Found with the current filter');</script>";
}


// Close connection
$conn->close();
?>
        </tbody>
    </table>

    <button onclick="exportToExcel()">Download as Excel</button>

    <script>
        function exportToExcel() {
    var table = document.getElementById("attendanceTable");
    var wb = XLSX.utils.table_to_book(table, {sheet:"Attendance"}); // Add sheet name

    // Add style for date and time formatting
    var style = wb.Sheets["Attendance"]['!rows'] = [];
    style[0] = {hpx: 25}; // Set header row height

    // Adjust column widths
    var ws = wb.Sheets["Attendance"];
    var range = XLSX.utils.decode_range(ws['!ref']);
    for (var C = range.s.c; C <= range.e.c; ++C) {
        ws['!cols'] = ws['!cols'] || [];
        if (C === 0) {
            // UID column width
            ws['!cols'][C] = { wch: 8 }; // Set width to accommodate 8 characters
        } else if (C === 1) {
            // Name column width
            ws['!cols'][C] = { wch: 16 }; // Set width to accommodate 16 characters
        } else {
            // Date/time column width
            ws['!cols'][C] = { wch: 20 }; // Set width to accommodate 20 characters
        }
    }

    // Add date and time formatting
    var dateStyle = {numFmt: "yyyy-mm-dd hh:mm:ss"}; // Date and time format
    for (var R = range.s.r + 1; R <= range.e.r; ++R) { // Skip header row
        for (var C = range.s.c + 2; C <= range.e.c; ++C) { // Adjust columns to start from third column
            var cell = ws[XLSX.utils.encode_cell({ c: C, r: R })];
            if (cell) {
                cell.z = dateStyle.numFmt;
            }
        }
    }

    var wbout = XLSX.write(wb, { bookType: 'xlsx', bookSST: true, type: 'binary' });
    function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
        return buf;
    }
    var blob = new Blob([s2ab(wbout)], { type: 'application/octet-stream' });
    var fileName = 'attendance_records.xlsx';
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
