<?php
session_start();
include("db.php");

/* USER AUTHENTICATION
   Ensure the user is logged in before
   allowing access to the dashboard. */

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* DASHBOARD STATISTICS
   Retrieve summary information displayed
   in the dashboard statistics cards. */

$total_projects = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM projects
         WHERE user_id='$user_id'"
    )
)['total'];

$total_tasks_stats = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM tasks
         WHERE user_id='$user_id'"
    )
)['total'];

$completed_tasks_stats = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM tasks
         WHERE user_id='$user_id'
         AND status='Completed'"
    )
)['total'];

$overdue_tasks = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM tasks
         WHERE user_id='$user_id'
         AND status!='Completed'
         AND deadline < CURDATE()"
    )
)['total'];

/* RETRIEVE USER PROJECTS
   Get all projects belonging to the
   currently logged-in user. */

$sql = "SELECT * FROM projects
        WHERE user_id = '$user_id'
        ORDER BY end_date ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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

    <h1>Welcome <?php echo $_SESSION['username']; ?></h1>

    <!-- Dashboard Statistics -->

    <div class="stats-grid">

        <div class="stat-card">
            <h3><?php echo $total_projects; ?></h3>
            <p>Projects</p>
        </div>

        <div class="stat-card">
            <h3><?php echo $total_tasks_stats; ?></h3>
            <p>Tasks</p>
        </div>

        <div class="stat-card">
            <h3><?php echo $completed_tasks_stats; ?></h3>
            <p>Completed</p>
        </div>

        <div class="stat-card">
            <h3><?php echo $overdue_tasks; ?></h3>
            <p>Overdue</p>
        </div>

    </div>

    <div class="top-buttons">
        <a href="create_project.php" class="btn">Create Project</a>
        <a href="logout.php" class="btn logout-btn">Logout</a>
    </div>

    <!-- User Projects -->

    <h2>My Projects</h2>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>

        <?php

        /* ==========================================
           PROJECT PROGRESS & STATUS CALCULATION
           Calculate progress percentage and
           determine the overall project status.
        ========================================== */

        // Current project being displayed
        $project_id = $row['project_id'];

        $total_tasks_query = mysqli_query(
            $conn,
            "SELECT COUNT(*) AS total
             FROM tasks
             WHERE project_id='$project_id'"
        );

        $total_tasks = mysqli_fetch_assoc($total_tasks_query)['total'];

        $completed_tasks_query = mysqli_query(
            $conn,
            "SELECT COUNT(*) AS completed
             FROM tasks
             WHERE project_id='$project_id'
             AND status='Completed'"
        );

        $completed_tasks = mysqli_fetch_assoc($completed_tasks_query)['completed'];

        $in_progress_query = mysqli_query(
            $conn,
            "SELECT COUNT(*) AS total
             FROM tasks
             WHERE project_id='$project_id'
             AND status='In Progress'"
        );

        $in_progress_tasks =
            mysqli_fetch_assoc($in_progress_query)['total'];

        // Calculate project completion percentage
        $progress = 0;

        if($total_tasks > 0)
        {
            $progress = round(($completed_tasks / $total_tasks) * 100);
        }

        // Determine overall project status
        if($total_tasks == 0)
        {
            $projectStatus = "No Tasks";
            $statusClass = "status-no-tasks";
        }
        elseif($completed_tasks == $total_tasks)
        {
            $projectStatus = "Completed";
            $statusClass = "status-completed";
        }
        elseif($in_progress_tasks > 0 || $completed_tasks > 0)
        {
            $projectStatus = "In Progress";
            $statusClass = "status-progress";
        }
        else
        {
            $projectStatus = "Pending";
            $statusClass = "status-pending";
        }

        ?>

        <div class="task-card">

            <p>
                <strong>Project Name:</strong><br>

                <a href="view_project.php?id=<?php echo $row['project_id']; ?>"
                   class="project-name-link">
                    <?php echo $row['project_name']; ?>
                </a>
            </p>

            <p>
                <strong>Project Description:</strong><br>
                <?php echo nl2br($row['description']); ?>
            </p>

            <p>
                <strong>Start Date:</strong>
                <?php echo $row['start_date']; ?>
            </p>

            <p>
                <strong>End Date:</strong>
                <?php echo $row['end_date']; ?>
            </p>

            <p>
                <strong>Status:</strong>

                <span class="status-badge <?php echo $statusClass; ?>">
                    <?php echo $projectStatus; ?>
                </span>
            </p>

            <!-- Project Progress Bar -->

            <div class="dashboard-progress">

                <div class="progress-title">
                    <span>Progress</span>
                    <span><?php echo $progress; ?>%</span>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill"
                         style="width: <?php echo $progress; ?>%;">
                    </div>
                </div>

                <div class="progress-info">
                    <?php echo $completed_tasks; ?>
                    of
                    <?php echo $total_tasks; ?>
                    tasks completed
                </div>

            </div>

            <br>

            <div class="button-group">

                <a href="view_project.php?id=<?php echo $row['project_id']; ?>" class="btn">
                    Open Project
                </a>

                <a href="edit_project.php?id=<?php echo $row['project_id']; ?>" class="btn">
                    Edit Project
                </a>

            </div>

        </div>

    <?php } ?>

</div>

</body>
</html>