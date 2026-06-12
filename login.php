<?php
session_start();
include("db.php");

/*  USER LOGIN
   Authenticate the user using their
   username and password credentials. */

$message = "";

if(isset($_POST['login']))
{
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    /* RETRIEVE USER ACCOUNT
       Search for a matching username. */

    $sql = "SELECT * FROM users
            WHERE username='$username'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1)
    {
        $user = mysqli_fetch_assoc($result);

        /* VERIFY PASSWORD
           Compare entered password against
           the stored password hash. */

        if(password_verify($password, $user['password']))
        {
            /* CREATE USER SESSION
               Store user details and redirect
               to the dashboard. */

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            header("Location: dashboard.php");
            exit();
        }
        else
        {
            $message = "Invalid Password";
        }
    }
    else
    {
        $message = "User not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>TaskFlow - Login</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<div class="login-container">

    <div class="login-card">

        <!-- Application Branding -->

        <div class="brand">

            <div class="brand-logo">
                TASKFLOW
            </div>

            <div class="brand-subtitle">
                Project Management System
            </div>

        </div>

        <!-- Display Login Errors -->

        <?php if(!empty($message)) { ?>
            <p class="error"><?php echo $message; ?></p>
        <?php } ?>

        <!-- Login Form -->

        <form method="POST">

            <label>Username</label>

            <input type="text"
                   name="username"
                   required>

            <label>Password</label>

            <input type="password"
                   name="password"
                   required>

            <input type="submit"
                   name="login"
                   value="Sign In">

        </form>

        <!-- Registration Link -->

        <p class="signup-link">
            Don't have an account?
            <a href="register.php">Sign Up</a>
        </p>

    </div>

</div>

</body>
</html>