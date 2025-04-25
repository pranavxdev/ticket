<?php
session_start();
include 'db.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle order deletion
if (isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];
    mysqli_query($conn, "DELETE FROM tickets WHERE id = $order_id");
    header("Location: manage_orders.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Manage Orders - DodoRave Admin</title>
    <link rel="stylesheet" href="admin.css" />
    <style>
        .orders-list {
            margin-top: 20px;
        }
        
        .order-item {
            background: #ffffff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #e5e5e5;
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .order-info {
            flex: 1;
        }
        
        .order-info h3 {
            margin: 0 0 5px 0;
            color: #000000;
        }
        
        .order-info p {
            margin: 0;
            color: #666666;
        }
        
        .order-actions {
            display: flex;
            gap: 10px;
        }
        
        .order-actions button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .delete-btn {
            background: #000000;
            color: #ffffff;
        }
        
        .delete-btn:hover {
            background: #333333;
        }
        
        .filter-bar {
            margin: 20px 0;
            display: flex;
            gap: 10px;
        }
        
        .filter-bar select {
            padding: 8px;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            background: #ffffff;
            color: #000000;
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
                <a href="index.php" class="homepage-btn"></i> Homepage</a>
                <a href="admin.php">Dashboard</a>
                <a href="manage_events.php">Manage Events</a>
                <a href="manage_users.php">Manage Users</a>
                <a href="manage_orders.php" class="active">Manage Orders</a>
                <a href="logout.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <h1>Manage Orders</h1>
            </header>
            
            <div class="filter-bar">
                <select id="eventFilter" onchange="filterOrders()">
                    <option value="">All Events</option>
                    <?php
                    $events_query = "SELECT id, title FROM events ORDER BY date DESC";
                    $events_result = mysqli_query($conn, $events_query);
                    while ($event = mysqli_fetch_assoc($events_result)) {
                        echo "<option value='{$event['id']}'>{$event['title']}</option>";
                    }
                    ?>
                </select>
                
                <select id="dateFilter" onchange="filterOrders()">
                    <option value="">All Dates</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
            
            <input type="text" class="search-box" placeholder="Search orders..." onkeyup="searchOrders(this.value)">
            
            <div class="orders-list">
                <?php
                $orders_query = "SELECT t.*, e.title as event_title, e.date as event_date, u.username, u.email 
                               FROM tickets t 
                               JOIN events e ON t.event_id = e.id 
                               JOIN users u ON t.user_id = u.id 
                               ORDER BY t.purchase_date DESC";
                $orders_result = mysqli_query($conn, $orders_query);
                
                while ($order = mysqli_fetch_assoc($orders_result)) {
                    echo "<div class='order-item' data-event='{$order['event_id']}' data-date='{$order['purchase_date']}'>
                        <div class='order-header'>
                            <div class='order-info'>
                                <h3>Order #{$order['id']}</h3>
                                <p>Event: {$order['event_title']}</p>
                                <p>Customer: {$order['username']} ({$order['email']})</p>
                                <p>Purchase Date: " . date('d M Y H:i', strtotime($order['purchase_date'])) . "</p>
                            </div>
                            <div class='order-actions'>
                                <form method='POST' style='display: inline;'>
                                    <input type='hidden' name='order_id' value='{$order['id']}'>
                                    <button type='submit' name='delete_order' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this order?\")'>Delete</button>
                                </form>
                            </div>
                        </div>
                        <div class='order-details'>
                            <p>Quantity: {$order['quantity']}</p>
                            <p>Event Date: {$order['event_date']}</p>
                        </div>
                    </div>";
                }
                ?>
            </div>
        </main>
    </div>
    
    <script>
        function filterOrders() {
            const eventFilter = document.getElementById('eventFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            const orders = document.querySelectorAll('.order-item');
            
            orders.forEach(order => {
                const eventId = order.dataset.event;
                const purchaseDate = new Date(order.dataset.date);
                const today = new Date();
                
                let showEvent = true;
                let showDate = true;
                
                if (eventFilter && eventId !== eventFilter) {
                    showEvent = false;
                }
                
                if (dateFilter) {
                    const diffTime = Math.abs(today - purchaseDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    
                    switch(dateFilter) {
                        case 'today':
                            showDate = diffDays <= 1;
                            break;
                        case 'week':
                            showDate = diffDays <= 7;
                            break;
                        case 'month':
                            showDate = diffDays <= 30;
                            break;
                    }
                }
                
                order.style.display = (showEvent && showDate) ? 'block' : 'none';
            });
        }
        
        function searchOrders(query) {
            const orders = document.querySelectorAll('.order-item');
            query = query.toLowerCase();
            
            orders.forEach(order => {
                const text = order.textContent.toLowerCase();
                order.style.display = text.includes(query) ? 'block' : 'none';
            });
        }
    </script>
</body>
</html> 