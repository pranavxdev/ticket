<?php session_start(); ?>
<?php include 'header.php';?>
<?php include 'db.php';

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $query = "SELECT * FROM events WHERE id = $event_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $event = mysqli_fetch_assoc($result);
    } else {
        echo "Event not found.";
        exit;
    }
} else {
    echo "No event ID provided.";
    exit;
}

// Handle ticket purchase
$purchase_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buy_tickets'])) {
    if (!isset($_SESSION['user_id'])) {
        $purchase_message = '<p class="error-message">Please <a href="login.php">login</a> to purchase tickets.</p>';
    } else {
        $quantity = intval($_POST['quantity']);
        if ($quantity > 0 && $quantity <= $event['total_tickets']) {
            $user_id = $_SESSION['user_id'];
            $insert_query = "INSERT INTO tickets (user_id, event_id, quantity) VALUES ($user_id, $event_id, $quantity)";
            
            if (mysqli_query($conn, $insert_query)) {
                // Update available tickets
                $new_total = $event['total_tickets'] - $quantity;
                mysqli_query($conn, "UPDATE events SET total_tickets = $new_total WHERE id = $event_id");
                $event['total_tickets'] = $new_total;
                $purchase_message = '<p class="success-message">Tickets purchased successfully!</p>';
            } else {
                $purchase_message = '<p class="error-message">Error purchasing tickets. Please try again.</p>';
            }
        } else {
            $purchase_message = '<p class="error-message">Invalid quantity selected.</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $event['title']; ?> - DodoRave</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #0f0f0f;
        }

        .ball {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(46, 146, 255, 0.15) 0%, rgba(46, 146, 255, 0) 70%);
            border-radius: 50%;
            z-index: -1;
        }

        .event-container {
            width: 64%;
            margin: 0 auto;
            padding: 0 0 48px 0;
        }

        .slide {
            background: rgba(26, 26, 26, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            margin-bottom: 32px;
            margin-top: 0;
        }

        .slide-container {
            padding: 32px;
            display: flex;
            align-items: center;
            gap: 48px;
            flex-wrap: wrap;
        }

        .slide-image {
            width: 400px;
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .slide-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .slide-info {
            flex: 1;
            min-width: 300px;
        }

        .slide-info h2 {
            font-size: 48px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            margin-bottom: 16px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .event-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .detail-card {
            background: rgba(25, 25, 25, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 16px;
            border-radius: 12px;
        }

        .detail-card h3 {
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 8px;
            color: white;
        }

        .detail-card p {
            font-size: 16px;
            color: #969696;
        }

        .ticket-section {
            background: rgba(15, 15, 15, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 32px;
            max-width: 500px;
        }

        .ticket-section h2 {
            font-size: 16px;
            margin-bottom: 16px;
            color: white;
            text-transform: uppercase;
            font-weight: 700;
        }

        .ticket-form {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
        }

        .ticket-form input[type="number"] {
            width: 60px;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(25, 25, 25, 0.4);
            color: white;
            font-size: 16px;
        }

        .buy-button {
            background: white;
            color: #0f0f0f;
            padding: 8px 22px;
            border: none;
            border-radius: 18px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .buy-button:hover {
            background: #e0e0e0;
        }

        .description {
            background: rgba(25, 25, 25, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 24px;
            border-radius: 12px;
            margin-top: 24px;
        }

        .description h2 {
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 16px;
            color: white;
        }

        .description p {
            font-size: 13px;
            line-height: 1.6;
            color: #969696;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
        }

        .success-message {
            color: #4ade80;
            margin-bottom: 15px;
        }

        .error-message a {
            color: #2e92ff;
            text-decoration: none;
        }

        @media (max-width: 1200px) {
            .event-container {
                width: 80%;
            }
        }

        @media (max-width: 768px) {
            .event-container {
                width: 90%;
            }
            
            .slide-container {
                flex-direction: column;
            }

            .slide-image {
                width: 100%;
                height: 250px;
            }

            .ticket-form {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .ticket-section {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="ball"></div>
    <div class="event-container">
        <div class="slide">
            <div class="slide-container">
                <div class="slide-image">
                    <img src="<?php echo $event['event_image']; ?>" alt="<?php echo $event['title']; ?>">
                </div>
                <div class="slide-info">
                    <h2><?php echo $event['title']; ?></h2>
                    <p style="color: #e0e0e0; font-size: 18px;">
                        <?php echo date('F j, Y', strtotime($event['date'])); ?> at <?php echo date('g:i A', strtotime($event['time'])); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="event-details">
            <div class="detail-card">
                <h3>Location</h3>
                <p><?php echo $event['location']; ?></p>
            </div>
            <div class="detail-card">
                <h3>Available Tickets</h3>
                <p><?php echo $event['total_tickets']; ?> remaining</p>
            </div>
            <div class="detail-card">
                <h3>Price per Ticket</h3>
                <p>MUR <?php echo number_format($event['ticket_price'], 2); ?></p>
            </div>
        </div>

        <div class="ticket-section">
            <h2>Tickets</h2>
            <?php echo $purchase_message; ?>
            <form method="POST" class="ticket-form">
                <div style="color: white;">
                    <p style="font-size: 16px; margin-bottom: 4px;">MUR <?php echo number_format($event['ticket_price'], 2); ?></p>
                </div>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <input type="number" name="quantity" min="1" max="<?php echo $event['total_tickets']; ?>" value="1" required>
                    <button type="submit" name="buy_tickets" class="buy-button">Buy</button>
                </div>
            </form>
        </div>

        <div class="description">
            <h2>Info</h2>
            <p><?php echo nl2br($event['description']); ?></p>
        </div>
    </div>

    <?php include 'footer.html'; ?>
</body>
</html>