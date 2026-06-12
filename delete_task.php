<?php
session_start();
include("db.php");

/* USER AUTHENTICATION
   Ensure the user is logged in before
   allowing task deletion. */

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

/* RETRIEVE PROJECT REFERENCE
   Obtain the parent project ID so the
   user can be redirected after deletion. */

$result = mysqli_query(
    $conn,
    "SELECT project_id
     FROM tasks
     WHERE task_id='$task_id'"
);

$task = mysqli_fetch_assoc($result);

$project_id = $task['project_id'];

/* DELETE TASK
   Remove the selected task from the
   database. */

mysqli_query(
    $conn,
    "DELETE FROM tasks
     WHERE task_id='$task_id'"
);

/* REDIRECT TO PROJECT PAGE */

header("Location: view_project.php?id=$project_id");
exit();
?>