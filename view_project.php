<?php
session_start();
include("db.php");

/* USER AUTHENTICATION
   Ensure the user is logged in before
   allowing access to project information.  */

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

/* RETRIEVE PROJECT DETAILS */

$sql = "SELECT * FROM projects
        WHERE project_id = '$project_id'";

$result = mysqli_query($conn, $sql);
$project = mysqli_fetch_assoc($result);

/* RETRIEVE PROJECT TASKS
   Display tasks ordered by deadline. */

$task_sql = "SELECT * FROM tasks
             WHERE project_id = '$project_id'
             ORDER BY deadline ASC";

$task_result = mysqli_query($conn, $task_sql);

/* PROJECT PROGRESS CALCULATION */

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

$progress = 0;

/* Calculate completion percentage */
if($total_tasks > 0)
{
    $progress = round(($completed_tasks / $total_tasks) * 100);
}

/* PROJECT STATUS CALCULATION
   Status is determined automatically
   from task progress. */

$in_progress_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM tasks
     WHERE project_id='$project_id'
     AND status='In Progress'"
);

$in_progress_tasks =
    mysqli_fetch_assoc($in_progress_query)['total'];

if($total_tasks == 0)
{
    $projectStatus = "No Tasks";
    $projectStatusClass = "status-no-tasks";
}
elseif($completed_tasks == $total_tasks)
{
    $projectStatus = "Completed";
    $projectStatusClass = "status-completed";
}
elseif($in_progress_tasks > 0 || $completed_tasks > 0)
{
    $projectStatus = "In Progress";
    $projectStatusClass = "status-progress";
}
else
{
    $projectStatus = "Pending";
    $projectStatusClass = "status-pending";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Project</title>
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

    <h1>Project Details</h1>

    <!-- Navigation Buttons -->

    <div class="button-group">

        <div class="top-buttons">

            <a href="add_task.php?project_id=<?php echo $project_id; ?>" class="btn">
                Add Task
            </a>

            <a href="dashboard.php" class="btn">
                Back to Dashboard
            </a>

            <a href="logout.php" class="btn logout-btn">
                Logout
            </a>

        </div>

    </div>

    <!-- Project Progress Section -->

    <div class="progress-section">

        <div class="progress-title">
            <span>Project Progress</span>
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

    <!-- Project Information -->

    <p>
        <strong>Project Name:</strong><br>
        <?php echo $project['project_name']; ?>
    </p>

    <p>
        <strong>Project Description:</strong><br>
        <?php echo nl2br($project['description']); ?>
    </p>

    <p>
        <strong>Start Date:</strong>
        <?php echo $project['start_date']; ?>
    </p>

    <p>
        <strong>End Date:</strong>
        <?php echo $project['end_date']; ?>
    </p>

    <p>
        <strong>Status:</strong>

        <span class="status-badge <?php echo $projectStatusClass; ?>">
            <?php echo $projectStatus; ?>
        </span>
    </p>

    <br>

    <h2>Project Tasks</h2>

    <?php while($task = mysqli_fetch_assoc($task_result)) { ?>

        <?php

        /* TASK PRIORITY STYLING */

        $priorityClass = "priority-low";

        if($task['priority'] == "High")
        {
            $priorityClass = "priority-high";
        }
        elseif($task['priority'] == "Medium")
        {
            $priorityClass = "priority-medium";
        }

        ?>

        <div class="task-card">

            <p>
                <strong>Task Name:</strong><br>

                <span class="task-name">
                    <?php echo $task['title']; ?>
                </span>
            </p>

            <p>
                <strong>Task Description:</strong><br>
                <?php echo nl2br($task['description']); ?>
            </p>

            <p>
                <strong>Priority:</strong>

                <span class="priority-badge <?php echo $priorityClass; ?>">
                    <?php echo $task['priority']; ?>
                </span>
            </p>

            <p>
                <strong>Deadline:</strong>
                <?php echo $task['deadline']; ?>

                <?php

                // Highlight overdue tasks that are not completed
                if(
                    $task['status'] != "Completed" &&
                    strtotime($task['deadline']) < strtotime(date("Y-m-d"))
                )
                {
                    echo '<span class="overdue-badge">Overdue</span>';
                }

                ?>

            </p>

            <!-- Task Status Update Form -->

            <form action="update_task_status.php"
                  method="POST"
                  class="button-group">

                <input type="hidden"
                       name="task_id"
                       value="<?php echo $task['task_id']; ?>">

                <input type="hidden"
                       name="project_id"
                       value="<?php echo $project_id; ?>">

                <select name="status">

                    <option value="Pending"
                        <?php if($task['status']=="Pending") echo "selected"; ?>>
                        Pending
                    </option>

                    <option value="In Progress"
                        <?php if($task['status']=="In Progress") echo "selected"; ?>>
                        In Progress
                    </option>

                    <option value="Completed"
                        <?php if($task['status']=="Completed") echo "selected"; ?>>
                        Completed
                    </option>

                </select>

                <input type="submit"
                       value="Update Status">

                <a href="edit_task.php?id=<?php echo $task['task_id']; ?>"
                   class="btn">
                    Edit Task
                </a>

                <a href="delete_task.php?id=<?php echo $task['task_id']; ?>"
                   class="btn logout-btn"
                   onclick="return confirm('Delete this task?')">
                    Delete Task
                </a>

            </form>

        </div>

    <?php } ?>

</div>

</body>
</html>