<?php
session_start();
include("db.php");

if(!isset($_GET['project_id']))
{
    header("Location: dashboard.php");
    exit();
}

$project_id = $_GET['project_id'];

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$message = "";

if(isset($_POST['submit']))
{
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];

    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO tasks
    (title, description, priority, deadline, status, user_id, project_id)
    VALUES
    ('$title', '$description', '$priority', '$deadline', 'Pending', '$user_id', '$project_id')";

    if(mysqli_query($conn, $sql))
    {
        header("Location: view_project.php?id=$project_id");
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
    <title>Add Task</title>
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


<h1>Add New Task</h1>

<div class="top-buttons">
<a href="logout.php" class="btn logout-btn">Logout</a>
</div>



    <p><?php echo $message; ?></p>

    <form method="POST">

        <label>Title</label>
        <input type="text" name="title" required>

        <label>Description</label>
        <textarea name="description"></textarea>

        <label>Priority</label>
        <select name="priority">
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select>

        <label>Deadline</label>
        <input type="date" name="deadline">

        <div class="button-group">

            <input type="submit"
                   name="submit"
                   value="Add Task">

            <a href="view_project.php?id=<?php echo $project_id; ?>" class="btn">
                Back to Project
            </a>

        </div>

    </form>

</div>

</body>
</html>