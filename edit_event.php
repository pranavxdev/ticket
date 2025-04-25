<?php
session_start();
include 'db.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Get event ID from URL
if (!isset($_GET['id'])) {
    header("Location: manage_events.php");
    exit;
}

$event_id = $_GET['id'];

// Get event data
$query = "SELECT * FROM events WHERE id = $event_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) != 1) {
    header("Location: manage_events.php");
    exit;
}

$event = mysqli_fetch_assoc($result);

// Handle form submission
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
    
    $update_query = "UPDATE events SET 
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
    
    if(mysqli_query($conn, $update_query)) {
        header("Location: manage_events.php");
        exit;
    } else {
        echo "Error updating event: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Event - DodoRave Admin</title>
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
                <h1>Edit Event</h1>
            </header>
            
            <div class="event-form">
                <form method="POST">
                    <div>
                        <label for="title">Event Title</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                    </div>
                    
                    <div>
                        <label for="event_image">Event Image</label>
                        <select id="event_image" name="event_image" required>
                            <?php
                            $assets_dir = "assets/";
                            $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
                            
                            if ($handle = opendir($assets_dir)) {
                                while (false !== ($file = readdir($handle))) {
                                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    if ($file != "." && $file != ".." && in_array($extension, $allowed_extensions)) {
                                        $selected = ($assets_dir . $file == $event['event_image']) ? 'selected' : '';
                                        echo "<option value='" . $assets_dir . $file . "' " . $selected . ">" . $file . "</option>";
                                    }
                                }
                                closedir($handle);
                            }
                            ?>
                        </select>
                        <div class="image-preview">
                            <img id="imagePreview" src="<?php echo htmlspecialchars($event['event_image']); ?>" alt="Selected Image Preview">
                        </div>
                    </div>
                    
                    <div>
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>
                    
                    <div>
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
                    </div>
                    
                    <div>
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" value="<?php echo $event['date']; ?>" required>
                    </div>
                    
                    <div>
                        <label for="time">Time</label>
                        <input type="time" id="time" name="time" value="<?php echo $event['time']; ?>" required>
                    </div>
                    
                    <div>
                        <label for="total_tickets">Total Tickets</label>
                        <input type="number" id="total_tickets" name="total_tickets" min="1" value="<?php echo $event['total_tickets']; ?>" required>
                    </div>
                    
                    <div>
                        <label for="ticket_price">Ticket Price (MUR)</label>
                        <input type="number" id="ticket_price" name="ticket_price" min="0" step="0.01" value="<?php echo $event['ticket_price']; ?>" required>
                    </div>
                    
                    <div>
                        <label for="featured">Featured Event</label>
                        <select id="featured" name="featured" required>
                            <option value="0" <?php echo $event['featured'] == 0 ? 'selected' : ''; ?>>No</option>
                            <option value="1" <?php echo $event['featured'] == 1 ? 'selected' : ''; ?>>Yes</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="save_event">Update Event</button>
                </form>
            </div>
        </main>
    </div>
    
    <script>
        // Add image preview functionality
        document.getElementById('event_image').addEventListener('change', function() {
            const preview = document.getElementById('imagePreview');
            preview.src = this.value;
        });
    </script>
</body>
</html> 