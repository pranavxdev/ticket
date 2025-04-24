<?php
session_start();
include 'db.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle event deletion
if (isset($_POST['delete_event'])) {
    $event_id = $_POST['event_id'];
    
    // First delete all tickets associated with this event
    mysqli_query($conn, "DELETE FROM tickets WHERE event_id = $event_id");
    
    // Then delete the event
    if(mysqli_query($conn, "DELETE FROM events WHERE id = $event_id")) {
        header("Location: manage_events.php");
        exit;
    } else {
        echo "Error deleting event: " . mysqli_error($conn);
    }
}

// Handle event creation/update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_event'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $total_tickets = intval($_POST['total_tickets']);
    $ticket_price = floatval($_POST['ticket_price']);
    $event_image = mysqli_real_escape_string($conn, $_POST['event_image']);
    
    if (isset($_POST['event_id'])) {
        // Update existing event
        $event_id = $_POST['event_id'];
        $query = "UPDATE events SET 
                  title = '$title',
                  description = '$description',
                  location = '$location',
                  date = '$date',
                  time = '$time',
                  total_tickets = $total_tickets,
                  ticket_price = $ticket_price,
                  event_image = '$event_image'
                  WHERE id = $event_id";
    } else {
        // Create new event
        $query = "INSERT INTO events (title, description, location, date, time, total_tickets, ticket_price, event_image) 
                  VALUES ('$title', '$description', '$location', '$date', '$time', $total_tickets, $ticket_price, '$event_image')";
    }
    
    mysqli_query($conn, $query);
    header("Location: manage_events.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Manage Events - DodoRave Admin</title>
    <link rel="stylesheet" href="admin.css" />
    <style>
        .event-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .event-form input[type="text"],
        .event-form input[type="number"],
        .event-form input[type="date"],
        .event-form input[type="time"],
        .event-form textarea,
        .event-form select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .event-form button {
            background: #111;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .event-form button:hover {
            background: #333;
        }

        .image-preview {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f9f9f9;
        }

        .image-preview img {
            max-width: 200px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        
        .events-list {
            margin-top: 20px;
        }
        
        .event-item {
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .event-info {
            display: flex;
            align-items: center;
        }

        .event-info img {
            border-radius: 4px;
            object-fit: cover;
            height: 100px;
        }
        
        .event-actions {
            display: flex;
            gap: 10px;
        }
        
        .event-actions button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .edit-btn {
            background: #4CAF50;
            color: white;
        }
        
        .delete-btn {
            background: #f44336;
            color: white;
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
                <a href="admin.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="manage_events.php" class="active"><i class="fas fa-calendar"></i> Manage Events</a>
                <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
                <a href="manage_orders.php"><i class="fas fa-ticket-alt"></i> Manage Orders</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <h1>Manage Events</h1>
            </header>
            
            <div class="event-form">
                <h2>Add New Event</h2>
                <form method="POST">
                    <div>
                        <label for="title">Event Title</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    
                    <div>
                        <label for="event_image">Event Image</label>
                        <select id="event_image" name="event_image" required>
                            <?php
                            $assets_dir = "assets/";
                            $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
                            $images = array();
                            
                            if ($handle = opendir($assets_dir)) {
                                while (false !== ($file = readdir($handle))) {
                                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    if ($file != "." && $file != ".." && in_array($extension, $allowed_extensions)) {
                                        echo "<option value='" . $assets_dir . $file . "'>" . $file . "</option>";
                                    }
                                }
                                closedir($handle);
                            }
                            ?>
                        </select>
                        <div class="image-preview" style="margin-top: 10px;">
                            <img id="imagePreview" src="" alt="Selected Image Preview" style="max-width: 200px; display: none;">
                        </div>
                    </div>
                    
                    <div>
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" required></textarea>
                    </div>
                    
                    <div>
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" required>
                    </div>
                    
                    <div>
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    
                    <div>
                        <label for="time">Time</label>
                        <input type="time" id="time" name="time" required>
                    </div>
                    
                    <div>
                        <label for="total_tickets">Total Tickets</label>
                        <input type="number" id="total_tickets" name="total_tickets" min="1" required>
                    </div>
                    
                    <div>
                        <label for="ticket_price">Ticket Price (MUR)</label>
                        <input type="number" id="ticket_price" name="ticket_price" min="0" step="0.01" required>
                    </div>
                    
                    <button type="submit" name="save_event">Save Event</button>
                </form>
            </div>
            
            <div class="events-list">
                <h2>Current Events</h2>
                <?php
                $events_query = "SELECT * FROM events ORDER BY date DESC";
                $events_result = mysqli_query($conn, $events_query);
                
                while ($event = mysqli_fetch_assoc($events_result)) {
                    $event_image = isset($event['event_image']) ? $event['event_image'] : 'assets/event1.jpg';
                    echo "<div class='event-item'>
                        <div class='event-info'>
                            <img src='{$event_image}' alt='{$event['title']}' style='max-width: 100px; margin-right: 15px;'>
                            <div>
                                <h3>{$event['title']}</h3>
                                <p>Date: {$event['date']} | Time: {$event['time']}</p>
                                <p>Location: {$event['location']}</p>
                                <p>Tickets: {$event['total_tickets']} | Price: MUR " . number_format($event['ticket_price'], 2) . "</p>
                            </div>
                        </div>
                        <div class='event-actions'>
                            <button class='edit-btn' onclick='editEvent({$event['id']})'>Edit</button>
                            <form method='POST' style='display: inline;'>
                                <input type='hidden' name='event_id' value='{$event['id']}'>
                                <button type='submit' name='delete_event' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this event?\")'>Delete</button>
                            </form>
                        </div>
                    </div>";
                }
                ?>
            </div>
        </main>
    </div>
    
    <script>
        function editEvent(eventId) {
            window.location.href = 'edit_event.php?id=' + eventId;
        }

        // Add image preview functionality
        document.getElementById('event_image').addEventListener('change', function() {
            const preview = document.getElementById('imagePreview');
            preview.src = this.value;
            preview.style.display = 'block';
        });

        // Show initial image preview
        window.addEventListener('load', function() {
            const imageSelect = document.getElementById('event_image');
            if (imageSelect.value) {
                const preview = document.getElementById('imagePreview');
                preview.src = imageSelect.value;
                preview.style.display = 'block';
            }
        });
    </script>
</body>
</html> 