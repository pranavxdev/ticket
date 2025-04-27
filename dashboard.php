<?php session_start(); ?>
<?php include 'header.php';?>
<?php include 'db.php';

// Check if user is logged in, redirect to login if not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get user information from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DodoRave</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Base styles */
        body {
            font-family: 'Inter', sans-serif;
            background: #0f0f0f;
        }

        /* Background gradient effect */
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

        /* Main container layout */
        .container {
            width: 64%;
            margin: 0 auto;
            padding-bottom: 48px;
        }

        /* User Profile Section Styles */
        .profile {
            background: rgba(26, 26, 26, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 24px;
            justify-content: space-between;
        }

        /* User profile layout */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        /* User avatar styling */
        .avatar {
            width: 80px;
            height: 80px;
            background: #2e92ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            color: white;
        }

        /* User information text styles */
        .user-info h2 {
            font-size: 24px;
            color: white;
            margin-bottom: 8px;
        }

        .user-info p {
            color: #969696;
            font-size: 14px;
        }

        /* Tickets Section Styles */
        .tickets {
            margin-top: 32px;
        }

        .tickets h3 {
            font-size: 18px;
            text-transform: uppercase;
            color: white;
            margin-bottom: 24px;
        }

        /* Ticket grid layout */
        .ticket-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }

        /* Individual ticket card styling */
        .ticket {
            background: rgba(26, 26, 26, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        /* Ticket image container */
        .ticket-img {
            width: 100%;
            height: 200px;
            position: relative;
        }

        .ticket-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Date badge styling */
        .date {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(48, 48, 48, 0.8);
            padding: 10px 12px;
            border-radius: 6px;
            color: white;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Ticket information section */
        .ticket-info {
            padding: 24px;
        }

        .ticket-info h4 {
            font-size: 20px;
            color: white;
            margin-bottom: 12px;
        }

        /* Ticket details grid */
        .details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .detail {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #969696;
            font-size: 14px;
        }

        .detail svg {
            width: 16px;
            height: 16px;
            color: #2e92ff;
        }

        /* Ticket footer section */
        .ticket-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .quantity {
            color: white;
            font-size: 14px;
        }

        .price {
            color: #2e92ff;
            font-size: 18px;
            font-weight: 700;
        }

        /* Logout button styling */
        .logout {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 8px 24px;
            border-radius: 20px;
            border: none;
            font-size: 14px;
            cursor: pointer;
            margin-left: auto;
            transition: all 0.3s ease;
        }

        .logout:hover {
            background: #dc2626;
            color: white;
        }

        /* Responsive design breakpoints */
        @media (max-width: 1200px) {
            .container {
                width: 80%;
            }
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
            }

            .ticket-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Background gradient element -->
    <div class="ball"></div>
    
    <!-- Main content container -->
    <div class="container">
        <!-- User Profile Section -->
        <div class="profile">
            <div class="user-profile">
                <!-- User avatar with first letter of username -->
                <div class="avatar">
                    <?php echo strtoupper(substr($username, 0, 1)); ?>
                </div>
                <!-- User information display -->
                <div class="user-info">
                    <h2>@<?php echo $username; ?></h2>
                    <p>Welcome back to your dashboard</p>
                </div>
            </div>

            <!-- Logout form -->
            <form action="logout.php" method="post">
                <button type="submit" class="logout">Log Out</button>
            </form>
        </div>

        <!-- Tickets Section -->
        <div class="tickets">
            <h3>My Tickets</h3>
            <div class="ticket-list">
                <?php
                $ticket_query = "SELECT 
                    e.id as event_id,
                    e.title, 
                    e.date, 
                    e.time, 
                    e.location, 
                    e.ticket_price,
                    e.event_image,
                    SUM(t.quantity) as total_quantity,
                    MAX(t.purchase_date) as purchase_date
                FROM tickets t 
                JOIN events e ON t.event_id = e.id 
                WHERE t.user_id = $user_id 
                GROUP BY e.id
                ORDER BY e.date ASC";

                $ticket_result = mysqli_query($conn, $ticket_query);

                if (mysqli_num_rows($ticket_result) > 0) {
                    while ($ticket = mysqli_fetch_assoc($ticket_result)) {
                        echo "<div class='ticket'>
                            <div class='ticket-img'>
                                <img src='{$ticket['event_image']}' alt='{$ticket['title']}'>
                                <div class='date'>
                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                        <path d='M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z'></path>
                                        <path d='M16 3l0 4'></path>
                                        <path d='M8 3l0 4'></path>
                                        <path d='M4 11l16 0'></path>
                                    </svg>
                                    " . date('d M Y', strtotime($ticket['date'])) . "
                                </div>
                            </div>
                            <div class='ticket-info'>
                                <h4>{$ticket['title']}</h4>
                                <div class='details'>
                                    <div class='detail'>
                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                            <circle cx='12' cy='12' r='10'></circle>
                                            <polyline points='12 6 12 12 16 14'></polyline>
                                        </svg>
                                        {$ticket['time']}
                                    </div>
                                    <div class='detail'>
                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                            <path d='M12 13a3 3 0 1 0 0 -6a3 3 0 0 0 0 6z'></path>
                                            <path d='M12 21c-4.418 0 -8 -3.582 -8 -8c0 -5.418 8 -13 8 -13s8 7.582 8 13c0 4.418 -3.582 8 -8 8z'></path>
                                        </svg>
                                        {$ticket['location']}
                                    </div>
                                </div>
                                <div class='ticket-footer'>
                                    <div class='quantity'>{$ticket['total_quantity']} tickets</div>
                                    <div class='price'>MUR " . number_format($ticket['ticket_price'] * $ticket['total_quantity'], 2) . "</div>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<p style='color: #969696; text-align: center;'>No tickets purchased yet.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <?php include 'footer.html'; ?>
</body>
</html>