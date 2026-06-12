<?php
include("db.php");

/* USER REGISTRATION
   Create a new user account and store
   login credentials securely. */

$message = "";

if(isset($_POST['register']))
{
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Securely hash the user's password
    $password = password_hash(
        $_POST['password'],
        PASSWORD_DEFAULT
    );

    /* CHECK FOR EXISTING ACCOUNT
       Prevent duplicate usernames or emails. */

    $check = mysqli_query(
        $conn,
        "SELECT *
         FROM users
         WHERE username='$username'
         OR email='$email'"
    );

    if(mysqli_num_rows($check) > 0)
    {
        $message = "Username or Email already exists.";
    }
    else
    {
        /* CREATE USER ACCOUNT
           Insert the new user into the database. */

        $sql = "INSERT INTO users
                (username, email, password)
                VALUES
                ('$username', '$email', '$password')";

        if(mysqli_query($conn, $sql))
        {
            $message = "Registration Successful! You can now sign in.";
        }
        else
        {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>TaskFlow - Register</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<div class="login-container">

    <div class="login-card">

        <!-- Application Branding -->

        <div class="brand">

            <a href="login.php" class="brand-logo">
                TASKFLOW
            </a>

            <div class="brand-subtitle">
                Create Your Account
            </div>

        </div>

        <!-- Display Registration Messages -->

        <?php if(!empty($message)) { ?>
            <p class="error"><?php echo $message; ?></p>
        <?php } ?>

        <!-- Registration Form -->

        <form method="POST">

            <label>Username</label>

            <input type="text"
                   name="username"
                   required>

            <label>Email Address</label>

            <input type="email"
                   name="email"
                   required>

            <label>Password</label>

            <input type="password"
                   name="password"
                   required>

            <input type="submit"
                   name="register"
                   value="Create Account">

        </form>

        <!-- Login Link -->

        <p class="signup-link">
            Already have an account?
            <a href="login.php">Sign In</a>
        </p>

    </div>

</div>

</body>
</html>