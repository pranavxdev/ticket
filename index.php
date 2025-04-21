<?php session_start() ?>
<?php include 'header.php';?>
<?php include 'db.php'; ?>

<style>
    <?php include 'styles.css' ?>
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
            <div class="slide">
                <div class="slide-container">
                    <div class="img-wrapper" style="width: 320px;">
                        <img src="/assets/event1.jpg" alt="" style="width: 100%; border-radius: 12px;">
                    </div>
                    
                    <div class="e-info">
                        <h2>Eco Festival #001</h2>
                    </div>
                </div>
            </div>
        </div>

        <section id="events">

            <h3 id="event">Events</h3>

<!--
            <div class="filter-bar">
                <button>All</button>
                <button>🎉 Festivals</button>
                <button>🍻 Bars</button>
                <button>🪩 Clubs</button>
            </div>
-->

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
                        <img src='assets/event1.jpg' alt='' style='width: 100%;'>
                        <h2> {$row['title']} </h2>
                        <a href='event.php?id={$row['id']}' class='info-btn'>More Info →</a>
                    </div>";
                }
            ?>
        </section>
    </main>

</body>
</html>

<?php
    include 'footer.html';

    mysqli_close($conn);
?>