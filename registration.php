<?php session_start() ?>
<?php include 'header.php';?>

<?php
include 'db.php';

$username = "";
$password = "";
$confirm_password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-pass'];

    // Simple validation
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    if (empty($confirm_password)) {
        $errors['confirm'] = "Confirm your password.";
    }

    if ($password !== $confirm_password) {
        $errors['confirm'] = "Passwords don't match.";
    }

    // Check if username exists in the database
    if (!empty($username)) {
        $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
        if (mysqli_num_rows($result) > 0) {
            $errors['username'] = "Username is already taken.";
        }
    }

    // If no errors, save user to the database
    if (count($errors) == 0) {
        mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");

        $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' LIMIT 1");
        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
        
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main id="login-main">
            <div class="ball"></div>
            <div class="login-container">
                <div class="text-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" width="52" height="52" stroke-width="2"> <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path> <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path> <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path> 
                    </svg>
        
                    <h2>Create your account</h2>
                    <p>Welcome! Let's get you started with a new Dodorave account. Please fill in the details below to continue.</p>
                    <p>Already a member? <a href="login.php" class="form-a">Log in</a></p>
                </div>
                
                <form action="" class="login-form" method="post">
                    <!-- Username Field -->
                    <label>Username:</label>
                    <input type="text" name="username" value="<?= $username ?>">
                    <?php if (!empty($errors['username'])): ?>
                        <p style="color:red;"><?= $errors['username'] ?></p>
                    <?php endif; ?>

                    <!-- Password Field -->
                    <label>Password:</label>
                    <input type="password" name="password">
                    <?php if (!empty($errors['password'])): ?>
                        <p style="color:red;"><?= $errors['password'] ?></p>
                    <?php endif; ?>

                    <!-- Confirm Password Field -->
                    <label>Confirm Password:</label>
                    <input type="password" name="confirm-pass">
                    <?php if (!empty($errors['confirm'])): ?>
                        <p style="color:red;"><?= $errors['confirm'] ?></p>
                    <?php endif; ?>

                    <!-- Submit Button -->
                    <input type="submit" value="Register">
                </form>

        </section>
        
    </main>
</body>
</html>

<?php
    include 'footer.html';
?>