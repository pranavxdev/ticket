<?php session_start() ?>
<?php include 'header.php';?>

<?php

include 'db.php';

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
        $purchase_message = '<p style="color: red;">Please <a href="login.php" style="color: #2e92ff;">login</a> to purchase tickets.</p>';
    } else {
        $quantity = intval($_POST['quantity']);
        if ($quantity > 0 && $quantity <= $event['total_tickets']) {
            $user_id = $_SESSION['user_id'];
            $insert_query = "INSERT INTO tickets (user_id, event_id, quantity) VALUES ($user_id, $event_id, $quantity)";
            
            if (mysqli_query($conn, $insert_query)) {
                // Update available tickets
                $new_total = $event['total_tickets'] - $quantity;
                mysqli_query($conn, "UPDATE events SET total_tickets = $new_total WHERE id = $event_id");
                
                // Refresh event data immediately
                $event['total_tickets'] = $new_total;
                
                $purchase_message = '<p style="color: green;">Tickets purchased successfully!</p>';
            } else {
                $purchase_message = '<p style="color: red;">Error purchasing tickets. Please try again.</p>';
            }
        } else {
            $purchase_message = '<p style="color: red;">Invalid quantity selected.</p>';
        }
    }
}
?>

<style>
    <?php include 'styles.css' ?>
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $event['title']; ?> - DodoRave</title>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .slide {
            background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
            border-radius: 16px;
            margin: 24px auto;
            width: 64%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        
        .slide-container {
            height: fit-content;
            padding: 32px;
            display: flex;
            align-items: center;
            gap: 48px;
            flex-wrap: wrap;
        }

        .img-wrapper {
            width: 400px;
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .e-info {
            flex: 1;
            min-width: 300px;
        }

        .e-info h2 {
            font-size: 48px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            margin-bottom: 16px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .e-info p {
            font-size: 18px;
            color: #e0e0e0;
            margin-bottom: 24px;
        }

        .container {
            margin: auto;
            width: 64%;
            padding: 24px 0px;
            color: #717070;
            display: flex;
            flex-direction: column;
        }

        .container p {
            font-size: 13px;
        }

        .ticket {
            padding-top: 24px;
        }

        .sale {
            margin-top: 12px;
            background-color: #0f0f0f;
            width: 500px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            color: white;
            padding: 12px 12px;
            border-radius: 12px;
        }

        .sale p {
            text-transform: uppercase;
            font-size: 16px;
            font-weight: 700;
            color: white;
        }

        .ticket-form {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ticket-form input[type="number"] {
            width: 60px;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #262626;
            background: rgba(25, 25, 25, 0.4);
            color: white;
        }

        .ticket-form input[type="submit"] {
            background-color: white;
            color: #0f0f0f;
            padding: 8px 22px;
            border-radius: 18px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .ticket-form input[type="submit"]:hover {
            background-color: #e0e0e0;
        }

        .desc {
            margin-top: 18px;
        }

        .event-details {
            margin-top: 24px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
        }

        .detail-item {
            background: rgba(25, 25, 25, 0.4);
            border: 1px solid #262626;
            border-radius: 12px;
            padding: 16px;
        }

        .detail-item h3 {
            color: white;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
        }

        .detail-item p {
            color: #969696;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="slide">
        <div class="slide-container">
            <div class="img-wrapper">
                <img src="assets/event1.jpg" alt="" style="width: 100%; border-radius: 12px;">
            </div>
                    
            <div class="e-info">
                <h2><?php echo $event['title']; ?></h2>
                <p><?php echo $event['date']; ?> at <?php echo $event['time']; ?></p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="event-details">
            <div class="detail-item">
                <h3>Location</h3>
                <p><?php echo $event['location']; ?></p>
            </div>
            <div class="detail-item">
                <h3>Available Tickets</h3>
                <p><?php echo $event['total_tickets']; ?> remaining</p>
            </div>
            <div class="detail-item">
                <h3>Price</h3>
                <p>MUR <?php echo number_format($event['ticket_price'], 2); ?></p>
            </div>
        </div>

        <div class="ticket">
            <h5>TICKETS</h5>
            <?php echo $purchase_message; ?>
            
            <div class="sale">
                <div class="ticket-info">
                    <h2>Ticket</h2>
                    <p>MUR <?php echo number_format($event['ticket_price'], 2); ?></p>
                </div>

                <form method="POST" class="ticket-form">
                    <input type="number" name="quantity" min="1" max="<?php echo $event['total_tickets']; ?>" value="1" required>
                    <input type="submit" name="buy_tickets" value="Buy">
                </form>
            </div>
        </div>

        <div class="desc">
            <h5>INFO</h5>
            <p><?php echo $event['description']; ?></p>
        </div>
    </div>
</body>
</html>

<?php
    include 'footer.html';
?>