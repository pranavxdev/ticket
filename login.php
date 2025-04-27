<?php session_start() ?>
<?php include 'header.php'; ?>

<?php
// Include database connection
include 'db.php';

// Initialize variables
$username = "";
$email = "";
$errors = [];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate form inputs
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    // If no validation errors, attempt login
    if (count($errors) == 0) {
        // Query database for user
        $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND email = '$email'");
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify password and create session if correct
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
            $errors['username'] = "No account found with that username and email.";
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
                <!-- Login form header with logo and welcome message -->
                <div class="text-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" width="52" height="52" stroke-width="2"> <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path> <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path> <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path> 
                    </svg>
        
                    <h2>Sign into Dodorave</h2>
                    <p>We're thrilled to see you again. Please sign in using your credentials or one of the methods below.</p>
                    <p>Not a member yet? <a href="registration.php" class="form-a">Create my account</a></p>
                </div>
                
                <!-- Login form with validation -->
                <form action="" class="login-form" method="post" onsubmit="return validateLoginForm()">
                    <label>Username:</label>
                    <input type="text" name="username" id="username" value="<?php echo $username; ?>">
                    <p id="username-error" style="color:red;"><?php if (!empty($errors['username'])) echo $errors['username']; ?></p>

                    <label>Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo $email; ?>">
                    <p id="email-error" style="color:red;"><?php if (!empty($errors['email'])) echo $errors['email']; ?></p>

                    <label>Password:</label>
                    <input type="password" name="password" id="password">
                    <p id="password-error" style="color:red;"><?php if (!empty($errors['password'])) echo $errors['password']; ?></p>

                    <input type="submit" value="Sign in & continue">
                </form>
        </section>
        
    </main>

    <script>
        // Client-side form validation
        function validateLoginForm() {
            let isValid = true;
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            
            // Clear previous error messages
            document.getElementById('username-error').textContent = '';
            document.getElementById('email-error').textContent = '';
            document.getElementById('password-error').textContent = '';
            
            // Validate username field
            if (username === '') {
                document.getElementById('username-error').textContent = 'Username is required.';
                isValid = false;
            }
            
            // Validate email field
            if (email === '') {
                document.getElementById('email-error').textContent = 'Email is required.';
                isValid = false;
            } else if (!isValidEmail(email)) {
                document.getElementById('email-error').textContent = 'Invalid email format.';
                isValid = false;
            }
            
            // Validate password field
            if (password === '') {
                document.getElementById('password-error').textContent = 'Password is required.';
                isValid = false;
            }
            
            return isValid;
        }
        
        // Email format validation helper function
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>
</body>
</html>

<?php
    include 'footer.html';
?>