<?php

/* DATABASE CONNECTION

   Establish a connection to the MySQL
   database used by the TaskFlow system. */

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "task_manager"
);

/* CONNECTION VALIDATION

   Stop execution if the database
   connection cannot be established. */

if(!$conn)
{
    die(
        "Connection failed: " .
        mysqli_connect_error()
    );
}

?>