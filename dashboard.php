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
        }

        .wallet-section h3 {
            font-size: larger;
            padding: 18px 2px;
        }

        .ticket-gallery {
            min-height: 300px;
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

        <div class="ticket-gallery">

        </div>
    </section>
        





</body>
</html>

<?php include 'footer.html' ?>