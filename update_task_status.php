<?php
session_start();
include("db.php");

/* USER AUTHENTICATION
   Ensure the user is logged in before
   updating any task information. */

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

/* UPDATE TASK STATUS
   Update the selected task status and
   return the user to the project page. */

if(isset($_POST['task_id']) && isset($_POST['status']))
{
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];
    $project_id = $_POST['project_id'];

    $sql = "UPDATE tasks
            SET status='$status'
            WHERE task_id='$task_id'";

    mysqli_query($conn, $sql);

    header("Location: view_project.php?id=$project_id");
    exit();
}

/* FALLBACK REDIRECT
   Return to dashboard if required data
   was not supplied. */

header("Location: dashboard.php");
exit();
?>