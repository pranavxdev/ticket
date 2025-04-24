<?php session_start() ?>
<?php include 'header.php'?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support - DodoRave</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .help-container {
            margin: auto;
            width: 64%;
            padding: 64px 0;
            color: white;
        }

        .help-header {
            margin-bottom: 48px;
            text-align: center;
        }

        .help-header h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .help-header p {
            color: #969696;
            font-size: 18px;
            line-height: 1.6;
        }

        .faq-section {
            margin-bottom: 64px;
        }

        .faq-grid {
            display: grid;
            gap: 24px;
        }

        .faq-item {
            background: rgba(25, 25, 25, 0.4);
            border: 1px solid #262626;
            border-radius: 12px;
            padding: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            background: rgba(35, 35, 35, 0.4);
        }

        .faq-question {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-answer {
            color: #969696;
            line-height: 1.6;
            display: none;
            padding-top: 12px;
            border-top: 1px solid #262626;
        }

        .support-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 48px;
        }

        .support-card {
            background: rgba(25, 25, 25, 0.4);
            border: 1px solid #262626;
            border-radius: 12px;
            padding: 32px;
            text-align: center;
        }

        .support-card svg {
            width: 48px;
            height: 48px;
            margin-bottom: 16px;
        }

        .support-card h3 {
            font-size: 20px;
            margin-bottom: 12px;
            color: #fff;
        }

        .support-card p {
            color: #969696;
            margin-bottom: 16px;
        }

        .support-card a {
            display: inline-block;
            padding: 8px 24px;
            background: #fff;
            color: #000;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .support-card a:hover {
            background: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="help-container">
        <div class="help-header">
            <h1>Help & Support</h1>
            <p>Find answers to common questions and get the support you need</p>
        </div>

        <div class="faq-section">
            <div class="faq-grid">
                <div class="faq-item">
                    <div class="faq-question">
                        How do I purchase tickets?
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        To purchase tickets, simply browse our events, select the event you're interested in, and click on "More Info". Choose your desired ticket quantity and click "Purchase". You'll need to be logged in to complete your purchase.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        How do I access my tickets?
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        After purchasing, your tickets will be available in your account dashboard under "My Wallet". You can view and download your tickets at any time. Make sure to bring them to the event, either printed or on your mobile device.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        What's your refund policy?
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Refunds are available up to 48 hours before the event start time. After this period, tickets are non-refundable. To request a refund, please contact our support team with your order details.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Can I transfer my tickets to someone else?
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Yes, tickets can be transferred to another user up to 24 hours before the event. To transfer tickets, go to your dashboard, select the tickets you want to transfer, and enter the recipient's email address.
                    </div>
                </div>
            </div>
        </div>

        <div class="support-section">
            <div class="support-card">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                <h3>Email Support</h3>
                <p>Send us an email and we'll get back to you within 24 hours.</p>
                <a href="mailto:support@dodorave.com">support@dodorave.com</a>
            </div>

            <div class="support-card">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                <h3>Phone Support</h3>
                <p>Call us directly for immediate assistance.</p>
                <a href="tel:4329909">432 9909</a>
            </div>
        </div>
    </div>

    <script>
        // Add click event listeners to FAQ items
        document.querySelectorAll('.faq-item').forEach(item => {
            item.addEventListener('click', () => {
                const answer = item.querySelector('.faq-answer');
                const arrow = item.querySelector('svg');
                
                // Toggle answer visibility
                if (answer.style.display === 'block') {
                    answer.style.display = 'none';
                    arrow.style.transform = 'rotate(0deg)';
                } else {
                    answer.style.display = 'block';
                    arrow.style.transform = 'rotate(180deg)';
                }
            });
        });
    </script>
</body>
</html>

<?php include 'footer.html' ?> 