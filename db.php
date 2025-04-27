<?php
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "dodorave";

    // Establish database connection
    $conn = mysqli_connect($servername, $username, $password, $db);

    // Check if connection was successful
    if(!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>