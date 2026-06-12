<?php
session_start();
include("db.php");

/* USER AUTHENTICATION
   Ensure the user is logged in before
   accessing the task editing page. */

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

/* TASK VALIDATION
   Ensure a task ID has been supplied. */

if(!isset($_GET['id']))
{
    header("Location: dashboard.php");
    exit();
}

$task_id = $_GET['id'];

/* RETRIEVE TASK DETAILS
   Load existing task information into
   the edit form. */

$sql = "SELECT * FROM tasks
        WHERE task_id='$task_id'";

$result = mysqli_query($conn, $sql);
$task = mysqli_fetch_assoc($result);

$message = "";

/* UPDATE TASK
   Save any changes made to the task. */

if(isset($_POST['update']))
{
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];

    $update_sql = "UPDATE tasks
                   SET
                   title='$title',
                   description='$description',
                   priority='$priority',
                   deadline='$deadline'
                   WHERE task_id='$task_id'";

    if(mysqli_query($conn, $update_sql))
    {
        header("Location: view_project.php?id=" . $task['project_id']);
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
    <title>Edit Task</title>
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

        <a href="logout.php" class="btn logout-btn">
            Logout
        </a>

    </div>

    <h1>Edit Task</h1>

    <!-- Display any database errors -->

    <p><?php echo $message; ?></p>

    <!-- Task Edit Form -->

    <form method="POST">

        <label>Title</label>

        <input type="text"
               name="title"
               value="<?php echo $task['title']; ?>"
               required>

        <label>Description</label>

        <textarea name="description"><?php echo $task['description']; ?></textarea>

        <label>Priority</label>

        <select name="priority">

            <option value="Low"
                <?php if($task['priority']=="Low") echo "selected"; ?>>
                Low
            </option>

            <option value="Medium"
                <?php if($task['priority']=="Medium") echo "selected"; ?>>
                Medium
            </option>

            <option value="High"
                <?php if($task['priority']=="High") echo "selected"; ?>>
                High
            </option>

        </select>

        <label>Deadline</label>

        <input type="date"
               name="deadline"
               value="<?php echo $task['deadline']; ?>">

        <!-- Task Actions -->

        <div class="button-group">

            <input type="submit"
                   name="update"
                   value="Update Task">

            <a href="view_project.php?id=<?php echo $task['project_id']; ?>"
               class="btn">
                Back to Project
            </a>

        </div>

    </form>

</div>

</body>
</html>