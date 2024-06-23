<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forget Password</title>
    <link rel="icon" href="./images/attendance-icon.png" />
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-color: #B0FC38;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
    }

    .container {
        background-color: rgba(255, 255, 255, 0.3); /* Semi-transparent white */
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2); /* Soft shadow */
        padding: 40px; /* Increased padding */
        border-radius: 10px;
        transition: transform 0.5s ease;
        width: 400px; /* Increased width */
        height: 300px; /* Increased height */
    }

    .login-form {
        text-align: center;
    }

    .login-form input[type="text"],
    .login-form input[type="password"] {
        width: 100%;
        padding: 12px; /* Increased padding for better spacing */
        margin-bottom: 15px; /* Increased margin for better separation */
        border: none; /* Removed border */
        border-radius: 5px;
        box-sizing: border-box;
        background-color: #99EDC390; /* Color shade for input fields */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Soft shadow */
    }

    .login-form input[type="submit"] {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px; /* Increased margin for better separation */
        border: none;
        border-radius: 5px;
        box-sizing: border-box;
        background-color: #03C04A;
        color: #ffffff;
        cursor: pointer;
    }

    .hidden {
        transform: translateY(100%);
    }

    .slide-in {
        animation: slideIn 0.5s ease forwards;
    }

    @keyframes slideIn {
        from {
            transform: translateY(100%);
        }
        to {
            transform: translateY(0);
        }
    }

    @media only screen and (max-width: 600px) {
        .container {
            width: 300px; /* Increased width */
            height: 300px; /* Increased height */
        }
    }
</style>
</head>
<body>

<div id="loginContainer" class="container hidden">
    <div class="login-form">
    <h2>Forget Password</h2>
    <form method="post" id="loginForm"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
        <input type="text" id="username" name="username" value="admin" readonly required><br>
        <input type="text" name="new_password" placeholder="Enter new password" required><br>
        <input type="submit" name="submit" value="Reset Password">
    </form>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var loginContainer = document.getElementById('loginContainer');
    loginContainer.classList.remove('hidden');
    loginContainer.classList.add('slide-in');
});
</script>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "smartattendancebyrfid";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Process the submitted form    
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the password in the database
    $sql = "UPDATE admins SET password='$new_password' WHERE username='$username'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Password updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating password: " . $conn->error . "');</script>";
    }

    $conn->close();
    
        // Password is correct, redirect to menu.html
        header("Location: login.php");
}
?>

</body>
</html>
