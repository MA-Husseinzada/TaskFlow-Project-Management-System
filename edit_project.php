<?php
session_start();
include("db.php");

/* USER AUTHENTICATION
   Ensure the user is logged in before
   accessing the project editing page. */

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

/* PROJECT VALIDATION
   Ensure a project ID has been supplied. */

if(!isset($_GET['id']))
{
    header("Location: dashboard.php");
    exit();
}

$project_id = $_GET['id'];

/* RETRIEVE PROJECT DETAILS
   Load the existing project information
   into the edit form. */

$sql = "SELECT * FROM projects
        WHERE project_id='$project_id'";

$result = mysqli_query($conn, $sql);
$project = mysqli_fetch_assoc($result);

$message = "";

/* UPDATE PROJECT
   Save any changes made to the project. */

if(isset($_POST['update']))
{
    $project_name = $_POST['project_name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $update_sql = "UPDATE projects
                   SET
                   project_name='$project_name',
                   description='$description',
                   start_date='$start_date',
                   end_date='$end_date'
                   WHERE project_id='$project_id'";

    if(mysqli_query($conn, $update_sql))
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
    <title>Edit Project</title>
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

    <h1>Edit Project</h1>

    <!-- Logout Button -->

    <div class="top-buttons">
        <a href="logout.php" class="btn logout-btn">
            Logout
        </a>
    </div>

    <!-- Display any database errors -->

    <p><?php echo $message; ?></p>

    <!-- Project Edit Form -->

    <form method="POST">

        <label>Project Name</label>

        <input type="text"
               name="project_name"
               value="<?php echo $project['project_name']; ?>"
               required>

        <label>Description</label>

        <textarea name="description"><?php echo $project['description']; ?></textarea>

        <label>Start Date</label>

        <input type="date"
               name="start_date"
               value="<?php echo $project['start_date']; ?>">

        <label>End Date</label>

        <input type="date"
               name="end_date"
               value="<?php echo $project['end_date']; ?>">

        <!-- Project Actions -->

        <div class="button-group">

            <input type="submit"
                   name="update"
                   value="Update Project">

            <a href="dashboard.php" class="btn">
                Back to Dashboard
            </a>

            <a href="delete_project.php?id=<?php echo $project_id; ?>"
               class="btn logout-btn"
               onclick="return confirm('Delete this project and all tasks?')">
                Delete Project
            </a>

        </div>

    </form>

</div>

</body>
</html>