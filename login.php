<?php session_start() ?>
<?php include 'header.php'; ?>

<?php
include 'db.php';

$username = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if fields are empty
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    if (count($errors) == 0) {
        $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify password
            if ($password == $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'];

                header("Location: dashboard.php");
                exit;
            } else {
                $errors['password'] = "Wrong password.";
            }
        } else {
            $errors['username'] = "No account found with that username.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodo Rave - Log in & Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main id="login-main">
            <div class="ball"></div>
            <div class="login-container">
                <div class="text-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" width="52" height="52" stroke-width="2"> <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path> <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path> <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path> 
                    </svg>
        
                    <h2>Sign into Dodorave</h2>
                    <p>We're thrilled to see you again. Please sign in using your credentials or one of the methods below.</p>
                    <p>Not a member yet? <a href="registration.php" class="form-a">Create my account</a></p>
                </div>
                
                <form action="" class="login-form" method="post">
                    <label>Username:</label>
                    <input type="text" name="username" value="<?php echo $username; ?>">
                    <?php if (!empty($errors['username'])) { echo "<p style='color:red'>" . $errors['username'] . "</p>"; } ?>

                    <label>Password:</label>
                    <input type="password" name="password"><br>
                    <?php if (!empty($errors['password'])) { echo "<p style='color:red'>" . $errors['password'] . "</p>"; } ?>

                    <input type="submit" value="Sign in & continue">
                </form>
        </section>
        
    </main>
</body>
</html>

<?php
    include 'footer.html';
?>