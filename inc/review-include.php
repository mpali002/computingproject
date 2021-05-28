<?php
    session_start();
    if(isset($_POST['submit'])) {
        //Creating variables for register inputs
        $type = $_POST['type'];
        $comment = $_POST['comment'];
        $shopId = $_POST['shopId'];
        $revId = $_SESSION['id'];
        date_default_timezone_set("Europe/London");
        $date = date('Y-m-d');
        $time = date("H:i:s"); 
        $rName = $_SESSION['name'];

        //Calling functions and database includes
        require_once('functions-include.php');
        require_once('database-include.php');

        //Creating error handlers

        //Checks if any inputs are empty
        if(emptyReviewInput($comment) !== false) {
            header('location:../writereview.php?error=emptyinput');
            exit();
        }
        createReview($connect, $rName, $type, $comment, $shopId, $revId, $date, $time);
    }
    else {
        header('location:../index.php');
    }