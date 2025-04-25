<?php
session_start();
include 'db.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle user deletion
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
    header("Location: manage_users.php");
    exit;
}

// Handle user status update
if (isset($_POST['toggle_admin'])) {
    $user_id = $_POST['user_id'];
    $is_admin = $_POST['is_admin'] == 1 ? 0 : 1;
    mysqli_query($conn, "UPDATE users SET is_admin = $is_admin WHERE id = $user_id");
    header("Location: manage_users.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Manage Users - DodoRave Admin</title>
    <link rel="stylesheet" href="admin.css" />
    <style>
        .users-list {
            margin-top: 20px;
        }
        
        .user-item {
            background: #ffffff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #e5e5e5;
        }
        
        .user-info {
            flex: 1;
        }
        
        .user-info h3 {
            margin: 0 0 5px 0;
            color: #000000;
        }
        
        .user-info p {
            margin: 0;
            color: #666666;
        }
        
        .user-actions {
            display: flex;
            gap: 10px;
        }
        
        .user-actions button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .admin-btn {
            background: #2563eb;
            color: #ffffff;
        }
        
        .admin-btn:hover {
            background: #1d4ed8;
        }
        
        .delete-btn {
            background: #dc2626;
            color: #ffffff;
        }
        
        .delete-btn:hover {
            background: #b91c1c;
        }
        
        .search-box {
            margin: 20px 0;
            padding: 10px;
            width: 100%;
            max-width: 300px;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            background: #ffffff;
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="logo">
                <h2>Admin Panel</h2>
            </div>
            <nav>
                <a href="index.php" class="homepage-btn">Homepage</a>
                <a href="admin.php">Dashboard</a>
                <a href="manage_events.php">Manage Events</a>
                <a href="manage_users.php" class="active">Manage Users</a>
                <a href="manage_orders.php">Manage Orders</a>
                <a href="logout.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <h1>Manage Users</h1>
            </header>
            
            <input type="text" class="search-box" placeholder="Search users..." onkeyup="searchUsers(this.value)">
            
            <div class="users-list">
                <?php
                $users_query = "SELECT * FROM users ORDER BY username ASC";
                $users_result = mysqli_query($conn, $users_query);
                
                while ($user = mysqli_fetch_assoc($users_result)) {
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
            </div>
        </main>
    </div>
    
    <script>
        function searchUsers(query) {
            // Create XMLHttpRequest object
            const xhr = new XMLHttpRequest();
            
            // Setup request
            xhr.open('GET', 'search_users.php?query=' + encodeURIComponent(query), true);
            
            // Setup handler for when request completes
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Update the users list with the response
                    document.querySelector('.users-list').innerHTML = xhr.responseText;
                }
            };
            
            // Send the request
            xhr.send();
        }
    </script>
</body>
</html> 