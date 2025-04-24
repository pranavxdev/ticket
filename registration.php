<?php session_start() ?>
<?php include 'header.php';?>

<?php
include 'db.php';

$username = "";
$email = "";
$password = "";
$confirm_password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-pass'];

    // Simple validation
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
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

    // Check if email exists in the database
    if (!empty($email)) {
        $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        if (mysqli_num_rows($result) > 0) {
            $errors['email'] = "Email is already registered.";
        }
    }

    // If no errors, save user to the database
    if (count($errors) == 0) {
        mysqli_query($conn, "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')");

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
    <script>
        function validateRegistrationForm() {
            let isValid = true;
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-pass').value;
            
            // Reset error messages
            document.getElementById('username-error').textContent = '';
            document.getElementById('email-error').textContent = '';
            document.getElementById('password-error').textContent = '';
            document.getElementById('confirm-error').textContent = '';
            
            // Validate username
            if (username === '') {
                document.getElementById('username-error').textContent = 'Username is required.';
                isValid = false;
            } else if (username.length < 3) {
                document.getElementById('username-error').textContent = 'Username must be at least 3 characters long.';
                isValid = false;
            }
            
            // Validate email
            if (email === '') {
                document.getElementById('email-error').textContent = 'Email is required.';
                isValid = false;
            } else if (!isValidEmail(email)) {
                document.getElementById('email-error').textContent = 'Invalid email format.';
                isValid = false;
            }
            
            // Validate password
            if (password === '') {
                document.getElementById('password-error').textContent = 'Password is required.';
                isValid = false;
            } else if (password.length < 6) {
                document.getElementById('password-error').textContent = 'Password must be at least 6 characters long.';
                isValid = false;
            }
            
            // Validate confirm password
            if (confirmPassword === '') {
                document.getElementById('confirm-error').textContent = 'Please confirm your password.';
                isValid = false;
            } else if (password !== confirmPassword) {
                document.getElementById('confirm-error').textContent = 'Passwords do not match.';
                isValid = false;
            }
            
            return isValid;
        }
        
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>
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
                
                <form action="" class="login-form" method="post" onsubmit="return validateRegistrationForm()">
                    <!-- Username Field -->
                    <label>Username:</label>
                    <input type="text" name="username" id="username" value="<?= $username ?>">
                    <p id="username-error" style="color:red;"><?php if (!empty($errors['username'])) echo $errors['username']; ?></p>

                    <!-- Email Field -->
                    <label>Email:</label>
                    <input type="email" name="email" id="email" value="<?= $email ?>">
                    <p id="email-error" style="color:red;"><?php if (!empty($errors['email'])) echo $errors['email']; ?></p>

                    <!-- Password Field -->
                    <label>Password:</label>
                    <input type="password" name="password" id="password">
                    <p id="password-error" style="color:red;"><?php if (!empty($errors['password'])) echo $errors['password']; ?></p>

                    <!-- Confirm Password Field -->
                    <label>Confirm Password:</label>
                    <input type="password" name="confirm-pass" id="confirm-pass">
                    <p id="confirm-error" style="color:red;"><?php if (!empty($errors['confirm'])) echo $errors['confirm']; ?></p>

                    <input type="submit" value="Create Account">
                </form>
        </section>
        
    </main>
</body>
</html>

<?php
    include 'footer.html';
?>