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
    $featured = intval($_POST['featured']);
    
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
                  event_image = '$event_image',
                  featured = $featured
                  WHERE id = $event_id";
    } else {
        // Create new event
        $query = "INSERT INTO events (title, description, location, date, time, total_tickets, ticket_price, event_image, featured) 
                  VALUES ('$title', '$description', '$location', '$date', '$time', $total_tickets, $ticket_price, '$event_image', $featured)";
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
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border: 1px solid #e5e5e5;
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
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            background: #ffffff;
            color: #000000;
        }
        
        .event-form button {
            background: #2563eb;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .event-form button:hover {
            background: #1d4ed8;
        }

        .image-preview {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            background: #ffffff;
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
            background: #ffffff;
            padding: 24px;
            margin-bottom: 24px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border: 1px solid #e5e5e5;
        }

        .event-info {
            display: flex;
            align-items: flex-start;
            gap: 32px;
            flex: 1;
        }

        .event-info img {
            width: 140px;
            height: 140px;
            border-radius: 8px;
            object-fit: cover;
        }

        .event-details {
            display: flex;
            flex-direction: column;
            gap: 16px;
            flex: 1;
        }

        .event-details h3 {
            font-size: 24px;
            font-weight: 600;
            color: #000000;
            margin: 0;
            letter-spacing: -0.5px;
            line-height: 1.2;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .featured-badge {
            background: #2563eb;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .event-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .event-meta p {
            font-size: 15px;
            color: #666666;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            line-height: 1.4;
        }

        .event-meta p svg {
            width: 18px;
            height: 18px;
            color: #666666;
            flex-shrink: 0;
        }

        .event-stats {
            display: flex;
            gap: 24px;
            margin-top: 8px;
            padding-top: 16px;
            border-top: 1px solid #e5e5e5;
        }

        .event-stat {
            font-size: 14px;
            color: #666666;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }

        .event-stat svg {
            width: 16px;
            height: 16px;
            color: #666666;
            flex-shrink: 0;
        }
        
        .event-actions {
            display: flex;
            gap: 12px;
            margin-left: 32px;
        }
        
        .event-actions button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 14px;
            min-width: 100px;
            text-align: center;
        }
        
        .edit-btn {
            background: #2563eb;
            color: #ffffff;
        }
        
        .edit-btn:hover {
            background: #1d4ed8;
        }
        
        .delete-btn {
            background: #dc2626;
            color: #ffffff;
        }
        
        .delete-btn:hover {
            background: #b91c1c;
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
                <a href="manage_events.php" class="active">Manage Events</a>
                <a href="manage_users.php">Manage Users</a>
                <a href="manage_orders.php">Manage Orders</a>
                <a href="logout.php">Logout</a>
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
                    
                    <div>
                        <label for="featured">Featured Event</label>
                        <select id="featured" name="featured" required>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
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
                            <img src='{$event_image}' alt='{$event['title']}'>
                            <div class='event-details'>
                                <h3>
                                    {$event['title']}
                                    " . ($event['featured'] == 1 ? '<span class="featured-badge">Featured</span>' : '') . "
                                </h3>
                                <div class='event-meta'>
                                    <p>
                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                            <path d='M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z'></path>
                                            <path d='M16 3l0 4'></path>
                                            <path d='M8 3l0 4'></path>
                                            <path d='M4 11l16 0'></path>
                                            <path d='M8 15h2v2h-2z'></path>
                                        </svg>
                                        " . date('d M Y', strtotime($event['date'])) . "
                                    </p>
                                    <p>
                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                            <circle cx='12' cy='12' r='10'></circle>
                                            <polyline points='12 6 12 12 16 14'></polyline>
                                        </svg>
                                        {$event['time']}
                                    </p>
                                    <p>
                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                            <path d='M12 13a3 3 0 1 0 0 -6a3 3 0 0 0 0 6z'></path>
                                            <path d='M12 21c-4.418 0 -8 -3.582 -8 -8c0 -5.418 8 -13 8 -13s8 7.582 8 13c0 4.418 -3.582 8 -8 8z'></path>
                                        </svg>
                                        {$event['location']}
                                    </p>
                                </div>
                                <div class='event-stats'>
                                    <div class='event-stat'>
                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                            <path d='M15 5h2a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2z'></path>
                                            <path d='M9 5h2a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2z'></path>
                                            <path d='M5 5h2a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2z'></path>
                                        </svg>
                                        {$event['total_tickets']} tickets
                                    </div>
                                    <div class='event-stat'>
                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                            <path d='M12 2v20'></path>
                                            <path d='M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6'></path>
                                        </svg>
                                        MUR " . number_format($event['ticket_price'], 2) . "
                                    </div>
                                </div>
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