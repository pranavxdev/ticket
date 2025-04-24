<?php
session_start();
include 'db.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    die("Unauthorized access");
}

// Get search query
$query = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';

// Prepare the search query
$search_query = "SELECT * FROM users WHERE 
                 username LIKE '%$query%' OR 
                 email LIKE '%$query%' 
                 ORDER BY username ASC";

$result = mysqli_query($conn, $search_query);

// Output the filtered users
while ($user = mysqli_fetch_assoc($result)) {
    // Skip the current admin user
    if ($user['id'] == $_SESSION['user_id']) continue;
    
    echo "<div class='user-item'>
        <div class='user-info'>
            <h3>{$user['username']}</h3>
            <p>Email: {$user['email']}</p>
            <p>Status: " . ($user['is_admin'] ? 'Admin' : 'User') . "</p>
        </div>
        <div class='user-actions'>
            <form method='POST' style='display: inline;'>
                <input type='hidden' name='user_id' value='{$user['id']}'>
                <input type='hidden' name='is_admin' value='{$user['is_admin']}'>
                <button type='submit' name='toggle_admin' class='admin-btn'>
                    " . ($user['is_admin'] ? 'Remove Admin' : 'Make Admin') . "
                </button>
            </form>
            <form method='POST' style='display: inline;'>
                <input type='hidden' name='user_id' value='{$user['id']}'>
                <button type='submit' name='delete_user' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</button>
            </form>
        </div>
    </div>";
}
?> 