<?php
session_start();
include("db.php");

/* USER AUTHENTICATION
   Ensure the user is logged in before
   allowing project deletion. */

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

/* DELETE PROJECT TASKS
   Remove all tasks associated with the
   selected project before deleting
   the project itself. */

mysqli_query(
    $conn,
    "DELETE FROM tasks
     WHERE project_id='$project_id'"
);

/*  DELETE PROJECT
   Remove the selected project from
   the database. */

mysqli_query(
    $conn,
    "DELETE FROM projects
     WHERE project_id='$project_id'"
);

/* REDIRECT TO DASHBOARD */

header("Location: dashboard.php");
exit();
?>