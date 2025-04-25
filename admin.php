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
$ticket_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) AS total FROM tickets"))['total'] ?? 0;
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
    <title>Dodo Rave Admin Panel</title>
    <link rel="stylesheet" href="admin.css" />
</head>
<body>
    <div class="admin-wrapper">
        <div class="sidebar">
            <div class="logo">
                <h2>Admin Panel</h2>
            </div>
            <nav>
                <a href="index.php" class="homepage-btn">Homepage</a>
                <a href="admin.php">Dashboard</a>
                <a href="manage_events.php">Manage Events</a>
                <a href="manage_users.php">Manage Users</a>
                <a href="manage_orders.php">Manage Orders</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>

        <main class="main-content">
            <header>
                <h1>Dashboard</h1>
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



