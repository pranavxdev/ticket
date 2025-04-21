<?php
session_start();
include 'db.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Example queries (replace with real ones from your DB)
$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$event_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM events"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>DodoRave Admin Panel</title>
  <link rel="stylesheet" href="admin.css" />
</head>
<body>
  <div class="admin-wrapper">
    <aside class="sidebar">
      <h2>DodoRave Admin</h2>
      <nav>
        <a href="#">Dashboard</a>
        <a href="#">Manage Events</a>
        <a href="#">Manage Users</a>
        <a href="#">View Orders</a>
        <a href="#">Logout</a>
      </nav>
    </aside>

    <main class="main-content">
      <header>
        <h1>Welcome, Admin</h1>
      </header>
      
      <section class="cards">
        <div class="card">
          <h3>Total Users</h3>
          <p>1,250</p>
        </div>
        <div class="card">
          <h3>Events</h3>
          <p>42</p>
        </div>
        <div class="card">
          <h3>Tickets Sold</h3>
          <p>5,670</p>
        </div>
        <div class="card">
          <h3>Revenue</h3>
          <p>Rs 175,000</p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>



