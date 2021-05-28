<?php

    if(isset($_POST['submit'])) {
        //Creating variables for register inputs
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $uname = $_POST['uname'];
        $email = $_POST['email'];
        $acctype = $_POST['acctype'];
        $postcode = $_POST['postcode'];
        $pw = $_POST['pw'];
        $pwRepeat = $_POST['pw-rpt'];

        //Calling functions and database includes
        require_once('functions-include.php');
        require_once('database-include.php');

        //Creating error handlers

        //Checks if any inputs are empty
        if(emptyRegisterInput($uname, $email, $pw, $pwRepeat) !== false) {
            header('location:../register.php?error=emptyinput');
            exit();
        }
        //Checks if the username matches the criteria
        if(invalidUname($uname, $fname, $lname) !== false) {
            header('location:../register.php?error=invaliduname');
            exit();
        }
        //Checks if the email is valid
        if(invalidEmail($email) !== false) {
            header('location:../register.php?error=invalidemail');
            exit();
        }
        //Checks if the passwords match
        if(pwMatch($pw, $pwRepeat) !== false) {
            header('location:../register.php?error=pwmatch');
            exit();
        //Checks if the username exists
        }
        if(uNameExists($uname, $email, $connect) !== false) {
            header('location:../register.php?error=unametaken');
            exit();
        }
        createUser($connect, $fname, $lname, $uname, $email, $acctype, $postcode, $pw);
    }
    else {
        header('location:../register.php');
    }