<?php
    session_start();
    if(isset($_POST['submit'])) {
        //Creating variables for register inputs
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $email = $_POST['email'];
        $id = $_SESSION['id'];

        //Calling functions and database includes
        require_once('functions-include.php');
        require_once('database-include.php');

        //Creating error handlers

        //Checks if any inputs are empty
        if(emptyEditInput($email) !== false) {
            header('location:../editprofile.php?error=emptyinput');
            exit();
        }
        //Checks if the email is valid
        if(invalidEmail($email) !== false) {
            header('location:../editprofile.php?error=invalidemail');
            exit();
        }
        //Passing new variables to users table
        editUser($connect, $fname, $lname, $email, $id);
    }
    else {
        //Redirecting to editprofile page if form was not submitted
        header('location:../editprofile.php');
    }