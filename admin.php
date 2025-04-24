<?php
session_start();
include 'db.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Get real statistics
$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$event_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM events"))['total'];
$ticket_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tickets"))['total'];
$revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(t.quantity * e.ticket_price) AS total FROM tickets t JOIN events e ON t.event_id = e.id"))['total'] ?? 0;

// Get recent orders
$recent_orders = mysqli_query($conn, "SELECT t.*, e.title as event_title, u.username 
                                    FROM tickets t 
                                    JOIN events e ON t.event_id = e.id 
                                    JOIN users u ON t.user_id = u.id 
                                    ORDER BY t.purchase_date DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DodoRave Admin Panel</title>
    <link rel="stylesheet" href="admin.css" />
    <style>
        .recent-orders {
            margin-top: 30px;
        }
        
        .recent-orders h2 {
            margin-bottom: 20px;
        }
        
        .order-list {
            background: white;
            border-radius: 10px;
            padding: 20px;
        }
        
        .order-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-item h3 {
            margin: 0 0 5px 0;
            color: #333;
        }
        
        .order-item p {
            margin: 0;
            color: #666;
        }
        
        .stats-card {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stats-card h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .stats-card p {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: #111;
        }
        
        .homepage-btn {
            background-color: #4CAF50;
            color: white;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }
        
        .homepage-btn:hover {
            background-color: #45a049;
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
                <a href="index.php" class="homepage-btn"><i class="fas fa-globe"></i> Homepage</a>
                <a href="admin.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
                <a href="manage_events.php"><i class="fas fa-calendar"></i> Manage Events</a>
                <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
                <a href="manage_orders.php"><i class="fas fa-ticket-alt"></i> Manage Orders</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <h1>Welcome, Admin</h1>
            </header>
            
            <section class="cards">
                <div class="stats-card">
                    <h3>Total Users</h3>
                    <p><?php echo $user_count; ?></p>
                </div>
                <div class="stats-card">
                    <h3>Events</h3>
                    <p><?php echo $event_count; ?></p>
                </div>
                <div class="stats-card">
                    <h3>Tickets Sold</h3>
                    <p><?php echo $ticket_count; ?></p>
                </div>
                <div class="stats-card">
                    <h3>Revenue</h3>
                    <p>MUR <?php echo number_format($revenue, 2); ?></p>
                </div>
            </section>
            
            <section class="recent-orders">
                <h2>Recent Orders</h2>
                <div class="order-list">
                    <?php
                    if (mysqli_num_rows($recent_orders) > 0) {
                        while ($order = mysqli_fetch_assoc($recent_orders)) {
                            echo "<div class='order-item'>
                                <h3>Order #{$order['id']}</h3>
                                <p>Event: {$order['event_title']}</p>
                                <p>Customer: {$order['username']}</p>
                                <p>Quantity: {$order['quantity']}</p>
                                <p>Date: " . date('d M Y H:i', strtotime($order['purchase_date'])) . "</p>
                            </div>";
                        }
                    } else {
                        echo "<p>No orders found.</p>";
                    }
                    ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>



