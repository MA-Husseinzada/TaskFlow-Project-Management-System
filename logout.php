<?php

/* USER LOGOUT
   End the current user session and
   return the user to the login page. */

session_start();

/* DESTROY SESSION
   Remove all stored session data. */

session_destroy();

/* REDIRECT TO LOGIN PAGE */

header("Location: login.php");
exit();

?>