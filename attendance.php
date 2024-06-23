<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Attendance List</title>
    <link rel="icon" href="./images/attendance-icon.png" />
  <style>
    body {
        background-color: #B0FC38;
        align-items: center;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
    }
    h1{
        font-size:50px;
        width:100%;
        text-align: center;
        background: -webkit-linear-gradient(#03C04A,#234F1E);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
  }
  .container-button {
    display: flex;
    justify-content: center;
}
  button{
        background: -webkit-linear-gradient(#03C04A,#234F1E);
        color: #fff;
        margin: auto;
        font-size: 25px;
        width: 200px;
        height: 40px;
  }
    table {
      border-collapse: collapse;
      width: 50%;
      margin: auto;
      margin-bottom: 20px; 
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

    .filter-form input[type="submit"] {
   width: 50%;
  padding: 5px;  
  color: #ffffff;
  cursor: pointer;
  background-color: #03C04A;
}
  .container {
    background-color: rgba(255, 255, 255, 0.3);
    display: flex;
    justify-content: center;
}
.filter-form input[type="text"],
.filter-form input[type="datetime-local"] {
    width: 80%;
    padding: 12px;
    margin-bottom: 15px;
    border: none;
    border-radius: 5px;
    box-sizing: border-box;
    background-color: #99EDC390;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.filter-form input[type="number"] {
    width: 100px; 
    padding: 5px;
    margin-bottom: 15px;
    border: none;
    border-radius: 5px;
    box-sizing: border-box;
    background-color: #99EDC390;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
}
.popup-link{
  display:flex;
  flex-wrap:wrap;
}

.popup-link a{
    background: #333;
    color: #fff;
    padding: 10px 30px;
    border-radius: 5px;
    font-size:17px;
    cursor:pointer;
    margin:20px;
    text-decoration:none;
}

.popup-container {
    visibility: hidden;
    opacity: 0;
    transition: all 0.3s ease-in-out;
    transform: scale(1.3);
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(21, 17, 17, 0.61);
    display: flex;
    align-items: center;
}
.popup-content {
    background-color: #B0FC38;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
}
.popup-content p{
    font-size: 17px;
    padding: 10px;
    line-height: 20px;
}
.popup-content a.close{
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    background: none;
    padding: 0;
    margin: 0;
    text-decoration:none;
}

.popup-content a.close:hover{
  color:#f00;
}

.popup-content span:hover,
.popup-content span:focus {
    color: #B0FC38;
    text-decoration: none;
    cursor: pointer;
}

.popup-container:target{
  visibility: visible;
  opacity: 1;
  transform: scale(1);
}

.popup-container h3{
  margin:10px;
}

.popup-style{
  transform: skewY(180deg);
   transition: all 0.7s ease-in-out;
}

.popup-style:target{
 transform: skewY(0deg);

 }
  </style>

</head>
<body>
<h1>Attendance Record Table</h1>


<a href="#popup7"><div class="container-button">
    <button><b>Login Filter</b></button>
</div></a>

<div id="popup7" class="popup-container popup-style">
  <div class="popup-content">
    <a href="#" class="close">&times;</a>
    <div id="filterContainer" class="container">
        <div class="filter-form">
            <h2>Apply Filter</h2>
            <form id="filterForm" method="post" action="filter.php">
                <input type="text" id="name" name="name" placeholder="Name"><br>
                Sem : <input type="number" id="student_sem" name="student_sem" min="1" max="6" placeholder="Semester"><br>
                Entry Time : <br><input type="datetime-local" id="entryTime" name="entryTime" placeholder="Entry Time"><br>
                Exit Time : <br><input type="datetime-local" id="exitTime" name="exitTime" placeholder="Exit Time"><br><br>

                <input type="submit" value="Filter" onclick="validateForm()">
            </form>
        </div>
    </div>
  </div>
</div>

<br><br>

<script>
        // Function to validate the form before submission
        function validateForm() {
            var entryTime = document.getElementById("entryTime").value;
            var exitTime = document.getElementById("exitTime").value;
    
            // Check if entry time is filled and exit time is empty
            if (entryTime && !exitTime) {
                alert("Please fill in the exit time.");
                return false; // Prevent form submission
            }
        }
</script>

    <!-- PHP code to include attendance data -->
    <?php include 'process.php'; ?>

</body>
</html>
