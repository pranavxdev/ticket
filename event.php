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
?>

<style>
    <?php include 'styles.css' ?>
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event</title>

    <style>
        body {
            font-family: 'inter';
        }
        .slide-container{
            height: fit-content;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 42px;
            flex-wrap: wrap;
        }

        .e-info {
            font-family: 'Inter';
        }

        .e-info h2{
            font-size: 48px;
            font-family: 'Inter';
            font-weight: 700;
            color: white;
            text-transform: uppercase;
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
            color: white;
            padding: 12px 12px;
            border-radius: 12px;
        }

        .sale p {
            text-transform: uppercase;
            font-size: 16px;
            font-weight: 700;
        }

        form input[type=submit] {
            background-color: white;
            color: #0f0f0f;
            padding: 8px 22px;
            border-radius: 18px;
            font-weight: 700;
        }

        .desc {
            margin-top: 18px;
        }

    </style>
</head>
<body>
    <div class="slide">
        <div class="slide-container">
            <div class="img-wrapper" style="width: 320px;">
                <img src="assets/event1.jpg" alt="" style="width: 100%; border-radius: 12px;">
            </div>
                    
            <div class="e-info">
                <h2><?php echo $event['title']; ?></h2>
                <p><?php echo $event['date']; ?></p>
            </div>

        </div>
    </div>

    <div class="container">
        <div class="ticket">
            <h5>TICKETS</h5>

            <div class="sale">

                <div class="ticket-info">
                    <h2>Ticket</h2>
                    <p>MUR <?php echo intval($event['ticket_price']); ?></p>
                </div>


                <form action="">
                    <input type="submit" value="Buy">
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