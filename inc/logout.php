<?php 
//Logging out, destroying the session
session_start();
session_unset();
session_destroy();
//Redirecting to home page
header("location:../index.php");