<?php session_start() ?>
<?php include 'header.php';?>
<?php include 'db.php'; ?>

<style>
    <?php include 'styles.css' ?>
    
    .slideshow {
        position: relative;
        width: 100%;
        height: 500px;
        overflow: hidden;
        background: #0f0f0f;
    }

    .slideshow-container {
        display: flex;
        transition: transform 0.5s ease-in-out;
        height: 100%;
    }

    .slide {
        min-width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .slide-content {
        display: flex;
        align-items: center;
        gap: 42px;
        padding: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .slide-image {
        width: 400px;
        height: 300px;
        border-radius: 12px;
        overflow: hidden;
    }

    .slide-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .slide-info {
        color: #000000;
        max-width: 500px;
    }

    .slide-info h2 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 16px;
        text-transform: uppercase;
        color: #000000;
    }

    .slide-info .event-details {
        display: flex;
        gap: 24px;
        margin-bottom: 24px;
    }

    .slide-info .event-detail {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #000000;
    }

    .slide-info .event-detail svg {
        width: 20px;
        height: 20px;
        stroke: #000000;
    }

    .slide-info .event-detail p {
        margin: 0;
        font-size: 16px;
        color: #000000;
    }

    .slide-info .description {
        font-size: 18px;
        color: #000000;
        margin-bottom: 24px;
        line-height: 1.5;
    }

    .slide-info a {
        display: inline-block;
        background: #000000;
        color: #ffffff;
        padding: 12px 24px;
        border-radius: 24px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .slide-info a:hover {
        background: #333333;
    }

    .slideshow-nav {
        position: absolute;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 12px;
    }

    .nav-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .nav-dot.active {
        background: white;
    }

    .slideshow-arrows {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        transform: translateY(-50%);
        display: flex;
        justify-content: space-between;
        padding: 0 24px;
    }

    .arrow {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .arrow:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .arrow svg {
        width: 24px;
        height: 24px;
        color: white;
    }
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodo Rave - Rave Events in Mauritius</title>
    <link rel="icon" type="image/png" href="assets/icon.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    
<main>
    <div class="slideshow">
        <div class="slideshow-container">
            <?php
            $sql = "SELECT * FROM events ORDER BY date ASC";
            $result = mysqli_query($conn, $sql);
            
            while($event = mysqli_fetch_assoc($result)) {
                echo "<div class='slide'>
                    <div class='slide-content'>
                        <div class='slide-image'>
                            <img src='assets/event1.jpg' alt='{$event['title']}'>
                        </div>
                        <div class='slide-info'>
                            <h2>{$event['title']}</h2>
                            <div class='event-details'>
                                <div class='event-detail'>
                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke-width='2' stroke-linejoin='round' stroke-linecap='round' stroke='currentColor'>
                                        <path d='M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z'></path>
                                        <path d='M16 3l0 4'></path>
                                        <path d='M8 3l0 4'></path>
                                        <path d='M4 11l16 0'></path>
                                        <path d='M8 15h2v2h-2z'></path>
                                    </svg>
                                    <p>{$event['date']}</p>
                                </div>
                                <div class='event-detail'>
                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke-width='2' stroke-linejoin='round' stroke-linecap='round' stroke='currentColor'>
                                        <circle cx='12' cy='12' r='10'></circle>
                                        <polyline points='12 6 12 12 16 14'></polyline>
                                    </svg>
                                    <p>{$event['time']}</p>
                                </div>
                                <div class='event-detail'>
                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke-width='2' stroke-linejoin='round' stroke-linecap='round' stroke='currentColor'>
                                        <path d='M12 13a3 3 0 1 0 0 -6a3 3 0 0 0 0 6z'></path>
                                        <path d='M12 21c-4.418 0 -8 -3.582 -8 -8c0 -5.418 8 -13 8 -13s8 7.582 8 13c0 4.418 -3.582 8 -8 8z'></path>
                                    </svg>
                                    <p>{$event['location']}</p>
                                </div>
                            </div>
                            <p class='description'>{$event['description']}</p>
                            <a href='event.php?id={$event['id']}'>Get Tickets</a>
                        </div>
                    </div>
                </div>";
            }
            ?>
        </div>
        <div class="slideshow-nav">
            <?php
            $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM events");
            $total_slides = mysqli_fetch_assoc($result)['total'];
            for($i = 0; $i < $total_slides; $i++) {
                echo "<div class='nav-dot" . ($i === 0 ? " active" : "") . "' data-slide='$i'></div>";
            }
            ?>
        </div>
        <div class="slideshow-arrows">
            <div class="arrow prev">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </div>
            <div class="arrow next">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </div>
        </div>
    </div>

    <section id="events">
        <h3 id="event">Events</h3>
        <div class="ticket-gallery">
            <?php
            $sql = "SELECT * FROM events";
            $result = mysqli_query($conn, $sql);

            while($row = $result->fetch_assoc()){
                echo "<div class='card'>
                <div class='date'>
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' width='20' height='20' stroke-width='2' stroke-linejoin='round' stroke-linecap='round' stroke='currentColor'>
                        <path d='M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z'></path>
                        <path d='M16 3l0 4'></path>
                        <path d='M8 3l0 4'></path>
                        <path d='M4 11l16 0'></path>
                        <path d='M8 15h2v2h-2z'></path>
                      </svg>
                    <p>{$row['date']}</p>
                </div>
                    <img src='{$row['event_image']}' alt='' style='width: 100%;'>
                    <h2> {$row['title']} </h2>
                    <a href='event.php?id={$row['id']}' class='info-btn'>More Info →</a>
                </div>";
            }
            ?>
        </div>
    </section>
</main>

<script>
    $(document).ready(function() {
        let currentSlide = 0;
        const $container = $('.slideshow-container');
        const $dots = $('.nav-dot');
        const totalSlides = $dots.length;

        function updateSlide() {
            $container.css('transform', `translateX(-${currentSlide * 100}%)`);
            $dots.removeClass('active').eq(currentSlide).addClass('active');
        }

        $('.next').click(function() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlide();
        });

        $('.prev').click(function() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlide();
        });

        $('.nav-dot').click(function() {
            currentSlide = $(this).data('slide');
            updateSlide();
        });

        // Auto-advance slides every 5 seconds
        setInterval(function() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlide();
        }, 5000);
    });
</script>

</body>
</html>

<?php
    include 'footer.html';
    mysqli_close($conn);
?>