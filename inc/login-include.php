<?php
    if(isset($_POST['submit'])) {
        $username = $_POST['name'];
        $pw = $_POST['pw'];
        //including database and functions files
        require_once('database-include.php');
        require_once('functions-include.php');

        //error handlers
        if(emptyLoginInput($username, $pw) !== false) {
            header('location:../login.php?error=emptyinput');
            exit();
        }
        //logging in to page
        loginUser($connect, $username, $pw);
    }
    else {
        header('location:../login.php');
    }