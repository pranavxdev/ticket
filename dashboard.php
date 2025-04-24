<?php 
session_start();
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

?>


<?php
    include 'db.php';

    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            color: white;
            font-family: 'Inter';
        }

        .admin-btn {
            text-decoration: none;
            color: rgb(72, 255, 0);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Inter';
            background-color: transparent;
            padding: 3px 16px;
            border: 2px solid green;
            border-radius: 12px;
        }

        .admin-user {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .user-info {
            margin: auto;
            width: 64%;
            border-bottom: 0.5px solid rgb(27, 27, 27);
            padding: 64px 2px 24px 2px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .pfp {
            width: 100px;
            height: 100px;
            border-radius: 360px;
            background-color: rgba(48,48,48,.8);
            backdrop-filter: blur(10px);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 42px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .wallet-section {
            margin: auto;
            width: 64%;
            padding: 24px 0;
        }

        .wallet-section h3 {
            font-size: 24px;
            padding: 18px 2px;
            margin-bottom: 24px;
        }

        .ticket-section {
            margin-bottom: 48px;
        }

        .ticket-section h4 {
            font-size: 18px;
            color: #969696;
            margin-bottom: 24px;
            padding-left: 2px;
        }

        .ticket-gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            width: 100%;
        }

        .card {
            width: 100%;
            background-color: black;
            border: 0.5px solid #202020;
            border-radius: 1rem;
            font-family: 'Inter';
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-start;
            position: relative;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card.past-event {
            opacity: 0.7;
        }

        .date {
            font-size: 14px;
            font-weight: 500;
            position: absolute;
            padding: 10px 12px;
            background-color: rgba(48,48,48,.8);
            backdrop-filter: blur(10px);
            color: white;
            margin: 12px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 1;
        }

        .card img {
            width: 100%;
            border-radius: 1rem 1rem 0 0;
            object-fit: cover;
            height: 200px;
        }

        .card h2 {
            font-weight: 600;
            font-size: 20px;
            padding: 12px;
            color: #fff;
        }

        .ticket-details {
            padding: 12px;
            background: rgba(25, 25, 25, 0.4);
            border: 1px solid #262626;
            border-radius: 12px;
            margin: 12px;
            width: calc(100% - 24px);
        }

        .ticket-details p {
            color: #969696;
            font-size: 14px;
            margin: 8px 0;
        }

        #logout {
            color: red;
            background-color: transparent;
            border: 2px solid red;
            border-radius: 6px;
            padding: 6px 56px;
            font-family: 'Inter';
            cursor: pointer;
            transition: 0.15s ease-in-out;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .ticket-gallery {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .ticket-gallery {
                grid-template-columns: 1fr;
            }
            
            .wallet-section {
                width: 90%;
            }
        }

        /* Animations */
        #logout:hover {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>

    <section class="user-info">
        <div class="pfp"> <?php echo substr($username, 0, 1)?> </div>

        <h2>@<?php echo $username ?></h2>

        <form action="logout.php" method="post" class="form">
            <button type="submit" id="logout">Log Out</button>
        </form>
    </section>

    <section class="wallet-section">
        <h3>My Wallet</h3>

        <?php
        // Get user's tickets with event details, grouped by event
        $ticket_query = "SELECT 
                           e.id as event_id,
                           e.title, 
                           e.date, 
                           e.time, 
                           e.location, 
                           e.ticket_price,
                           SUM(t.quantity) as total_quantity,
                           MAX(t.purchase_date) as purchase_date
                       FROM tickets t 
                       JOIN events e ON t.event_id = e.id 
                       WHERE t.user_id = $user_id 
                       GROUP BY e.id
                       ORDER BY e.date ASC, t.purchase_date DESC";
        $ticket_result = mysqli_query($conn, $ticket_query);

        if (mysqli_num_rows($ticket_result) > 0) {
            $upcoming_tickets = [];
            $past_tickets = [];
            $current_date = date('Y-m-d');

            while ($ticket = mysqli_fetch_assoc($ticket_result)) {
                if ($ticket['date'] >= $current_date) {
                    $upcoming_tickets[] = $ticket;
                } else {
                    $past_tickets[] = $ticket;
                }
            }

            // Display Upcoming Events
            if (!empty($upcoming_tickets)) {
                echo "<div class='ticket-section'>
                    <h4>Upcoming Events</h4>
                    <div class='ticket-gallery'>";
                
                foreach ($upcoming_tickets as $ticket) {
                    echo "<div class='card'>
                        <div class='date'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' width='20' height='20' stroke-width='2' stroke-linejoin='round' stroke-linecap='round' stroke='currentColor'>
                                <path d='M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z'></path>
                                <path d='M16 3l0 4'></path>
                                <path d='M8 3l0 4'></path>
                                <path d='M4 11l16 0'></path>
                                <path d='M8 15h2v2h-2z'></path>
                            </svg>
                            <p>{$ticket['date']}</p>
                        </div>
                        <img src='assets/event1.jpg' alt='' style='width: 100%;'>
                        <h2>{$ticket['title']}</h2>
                        <div class='ticket-details'>
                            <p>Quantity: {$ticket['total_quantity']}</p>
                            <p>Total: MUR " . number_format($ticket['ticket_price'] * $ticket['total_quantity'], 2) . "</p>
                            <p>Location: {$ticket['location']}</p>
                            <p>Time: {$ticket['time']}</p>
                            <p>Purchased: " . date('d M Y', strtotime($ticket['purchase_date'])) . "</p>
                        </div>
                    </div>";
                }
                echo "</div></div>";
            }

            // Display Past Events
            if (!empty($past_tickets)) {
                echo "<div class='ticket-section'>
                    <h4>Past Events</h4>
                    <div class='ticket-gallery'>";
                
                foreach ($past_tickets as $ticket) {
                    echo "<div class='card'>
                        <div class='date'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' width='20' height='20' stroke-width='2' stroke-linejoin='round' stroke-linecap='round' stroke='currentColor'>
                                <path d='M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z'></path>
                                <path d='M16 3l0 4'></path>
                                <path d='M8 3l0 4'></path>
                                <path d='M4 11l16 0'></path>
                                <path d='M8 15h2v2h-2z'></path>
                            </svg>
                            <p>{$ticket['date']}</p>
                        </div>
                        <img src='assets/event1.jpg' alt='' style='width: 100%;'>
                        <h2>{$ticket['title']}</h2>
                        <div class='ticket-details'>
                            <p>Quantity: {$ticket['total_quantity']}</p>
                            <p>Total: MUR " . number_format($ticket['ticket_price'] * $ticket['total_quantity'], 2) . "</p>
                            <p>Location: {$ticket['location']}</p>
                            <p>Time: {$ticket['time']}</p>
                            <p>Purchased: " . date('d M Y', strtotime($ticket['purchase_date'])) . "</p>
                        </div>
                    </div>";
                }
                echo "</div></div>";
            }
        } else {
            echo "<p>You haven't purchased any tickets yet.</p>";
        }
        ?>
    </section>
        





</body>
</html>

<?php include 'footer.html' ?>