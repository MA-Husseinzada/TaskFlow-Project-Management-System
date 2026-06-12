<?php
session_start();
include("db.php");

/* USER AUTHENTICATION
   Ensure the user is logged in before
   accessing the project creation page. */

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$message = "";

/* CREATE NEW PROJECT
   Insert a new project linked to the
   currently logged-in user. */

if(isset($_POST['create_project']))
{
    $project_name = $_POST['project_name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO projects
            (project_name, description, start_date, end_date, user_id)
            VALUES
            ('$project_name', '$description', '$start_date', '$end_date', '$user_id')";

    if(mysqli_query($conn, $sql))
    {
        header("Location: dashboard.php");
        exit();
    }
    else
    {
        $message = mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Project</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<div class="container">

    <div class="app-header">

        <div>

            <a href="dashboard.php" class="brand-logo">
                TASKFLOW
            </a>

            <div class="brand-subtitle">
                Project Management System
            </div>

        </div>

    </div>

    <h1>Create Project</h1>

    <!-- Logout Button -->

    <div class="top-buttons">

        <a href="logout.php" class="btn logout-btn">
            Logout
        </a>

    </div>

    <!-- Display any database errors -->

    <p><?php echo $message; ?></p>

    <!-- Project Creation Form -->

    <form method="POST">

        <label>Project Name</label>

        <input type="text"
               name="project_name"
               required>

        <label>Description</label>

        <textarea name="description"></textarea>

        <label>Start Date</label>

        <input type="date"
               name="start_date">

        <label>End Date</label>

        <input type="date"
               name="end_date">

        <!-- Project Actions -->

        <div class="button-group">

            <input type="submit"
                   name="create_project"
                   value="Create Project">

            <a href="dashboard.php" class="btn">
                Back to Dashboard
            </a>

        </div>

    </form>

</div>

</body>
</html>