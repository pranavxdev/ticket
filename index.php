<?php session_start() ?>
<?php include 'header.php';?>
<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodo Rave - Rave Events in Mauritius</title>
    <link rel="icon" type="image/png" href="assets/icon.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
<main>
    <div class="ball"></div>
    <div class="slideshow">
        <div class="slideshow-container">
            <?php
            $sql = "SELECT * FROM events WHERE featured = 1 ORDER BY date ASC";
            $result = mysqli_query($conn, $sql);
            
            while($event = mysqli_fetch_assoc($result)) {
                echo "<div class='slide'>
                    <div class='slide-content'>
                        <div class='slide-image'>
                            <img src='{$event['event_image']}' alt='{$event['title']}'>
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
                            <a href='event.php?id={$event['id']}'>More Info →</a>
                        </div>
                    </div>
                </div>";
            }
            ?>
        </div>
        <div class="slideshow-nav">
            <?php
            $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM events WHERE featured = 1");
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
                    <div class='card-info'>
                        <h2> {$row['title']} </h2>
                        <a href='event.php?id={$row['id']}' class='info-btn'>More Info →</a>
                    </div>
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