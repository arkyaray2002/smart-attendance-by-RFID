<?php
include 'conn.php';

// Function to get the name associated with the RFID UID
function getName($rfid_uid)
{
    global $conn;
    $stmt = $conn->prepare("SELECT name FROM teachers WHERE UPPER(rfid_uid) = ? UNION SELECT name FROM students WHERE UPPER(rfid_uid) = ?");
    $stmt->execute([$rfid_uid, $rfid_uid]);
    $result = $stmt->fetch();
    return $result ? $result['name'] : null;
}

// Function to check if RFID UID belongs to a teacher
function isTeacher($rfid_uid)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM teachers WHERE UPPER(rfid_uid) = ?");
    $stmt->execute([$rfid_uid]);
    return $stmt->fetch();
}

// Function to check if RFID UID belongs to a student
function isStudent($rfid_uid)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM students WHERE UPPER(rfid_uid) = ?");
    $stmt->execute([$rfid_uid]);
    return $stmt->fetch();
}

// Function to toggle entry/exit based on previous attendance records
function toggleEntryExit($rfid_uid)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM attendance WHERE UPPER(rfid_uid) = ? ORDER BY entry_time DESC LIMIT 1");
    $stmt->execute([$rfid_uid]);
    $last_entry = $stmt->fetch();

    if ($last_entry && $last_entry['exit_time'] === null) {
        // Last record found and exit time is NULL, so update exit time
        $stmt = $conn->prepare("UPDATE attendance SET exit_time = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$last_entry['id']]);
    } else {
        // Last record not found or exit time is already set, so insert new entry
        $name = getName($rfid_uid);
        if ($name) {
            $stmt = $conn->prepare("INSERT INTO attendance (rfid_uid, name, entry_time) VALUES (?, ?, CURRENT_TIMESTAMP)");
            return $stmt->execute([$rfid_uid, $name]);
        } else {
            return false;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    switch ($_POST['action']) {
        case 'insertRecord':
            if (isset($_POST['cardid'])) {
                $rfid_uid = strtoupper($_POST['cardid']); // Convert RFID UID to uppercase

                // Check if RFID UID belongs to a teacher or student
                if (isTeacher($rfid_uid) || isStudent($rfid_uid)) {
                    // RFID UID found in teacher or student database, proceed with attendance
                    if (toggleEntryExit($rfid_uid)) {
                        http_response_code(200);
                        echo "success";
                    } else {
                        http_response_code(500);
                        echo "Internal Server Error";
                    }
                } else {
                    // RFID UID not found in teacher or student database, ignore entry
                    http_response_code(404);
                    echo "RFID not recognized";
                }
            } else {
                http_response_code(400);
                echo "Bad Request: cardid not set";
            }
            break;
        default:
            http_response_code(400);
            echo "Bad Request: action not recognized";
            break;
    }
} else {
    http_response_code(405);
    //echo "Method Not Allowed";
}
?>
