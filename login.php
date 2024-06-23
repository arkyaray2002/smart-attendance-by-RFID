<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Page</title>
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
        background-color: rgba(255, 255, 255, 0.3);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
        padding: 40px;
        border-radius: 10px;
        transition: transform 0.5s ease;
        width: 400px;
        height: 300px;
    }

    .login-form {
        text-align: center;
    }

    .login-form input[type="text"],
    .login-form input[type="password"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: none;
        border-radius: 5px;
        box-sizing: border-box;
        background-color: #99EDC390;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .login-form input[type="submit"] {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px;
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
            width: 300px;
            height: 300px;
        }
    }
</style>
</head>
<body>

<div id="loginContainer" class="container hidden">
    <div class="login-form">
        <h2>Login</h2>
        <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="text" id="username" name="username" placeholder="Username" required><br><br>
            <input type="password" id="password" name="password" placeholder="Password" required><br>
            <a href="./forget_password.php" style="color:green">Forget Password ?</a><br><br>
            <input type="submit" value="Login">
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
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "smartattendancebyrfid";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT username, password FROM admins WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // Password is correct, set session variable and redirect to menu.php
        $_SESSION['username'] = $username;
        header("Location: menu.php");
        exit();
    } else {
        // Invalid username or password
        echo "<script>alert('Invalid username or password');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

</body>
</html>
