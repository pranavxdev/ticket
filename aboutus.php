<?php session_start() ?>
<?php include 'header.php'?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - DodoRave</title>
    <link rel="stylesheet" href="styles.css">
    <style>

        body {
            font-family: 'Inter';
        }

        .admin-user {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .about-container {
            margin: auto;
            width: 64%;
            padding: 64px 0;
            color: white;
        }

        .about-header {
            margin-bottom: 48px;
        }

        .about-header h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .about-header p {
            color: #969696;
            font-size: 18px;
            line-height: 1.6;
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            margin-bottom: 64px;
        }

        .about-section {
            background: rgba(25, 25, 25, 0.4);
            border: 1px solid #262626;
            border-radius: 12px;
            padding: 32px;
        }

        .about-section h2 {
            font-size: 24px;
            margin-bottom: 16px;
            color: #fff;
        }

        .about-section p {
            color: #969696;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .contact-section {
            margin-top: 64px;
            text-align: center;
        }

        .contact-section h2 {
            font-size: 32px;
            margin-bottom: 24px;
            color: #fff;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 48px;
            margin-top: 32px;
        }

        .contact-item {
            text-align: center;
        }

        .contact-item h3 {
            color: #fff;
            margin-bottom: 8px;
        }

        .contact-item p {
            color: #969696;
        }
    </style>
</head>
<body>
    <div class="about-container">
        <div class="about-header">
            <h1>About DodoRave</h1>
            <p>Your premier destination for event ticketing in Mauritius. We connect people with unforgettable experiences, making it easy to discover and book tickets for the best events across the island.</p>
        </div>

        <div class="about-content">
            <div class="about-section">
                <h2>Our Mission</h2>
                <p>At DodoRave, we're passionate about bringing people together through amazing events. Our mission is to simplify the ticket booking process while ensuring everyone has access to the best entertainment experiences Mauritius has to offer.</p>
                <p>We believe in creating a seamless connection between event organizers and attendees, fostering a vibrant entertainment culture across the island.</p>
            </div>

            <div class="about-section">
                <h2>What We Do</h2>
                <p>DodoRave is your one-stop platform for discovering and booking tickets to various events, from music festivals and concerts to beach parties and cultural gatherings.</p>
                <p>We provide a secure, user-friendly platform that makes it easy to browse events, purchase tickets, and manage your bookings all in one place.</p>
            </div>
        </div>

        <div class="contact-section">
            <h2>Get in Touch</h2>
            <p>Have questions or want to learn more about DodoRave? We'd love to hear from you!</p>
            <div class="contact-info">
                <div class="contact-item">
                    <h3>Email</h3>
                    <p>support@dodorave.com</p>
                </div>
                <div class="contact-item">
                    <h3>Phone</h3>
                    <p>432 9909</p>
                </div>
                <div class="contact-item">
                    <h3>Location</h3>
                    <p>Port Louis, Mauritius</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php include 'footer.html' ?>