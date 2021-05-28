<?php
    session_start();
    if(isset($_POST['edit'])) {
        //Creating variables for register inputs
        $title = $_POST['title'];
        $summary = $_POST['summary'];
        $content = $_POST['content'];
        $id = $_POST['id'];

        //Calling functions and database includes
        require_once('functions-include.php');
        require_once('database-include.php');

        //Creating error handlers

        //Checks if any inputs are empty
        if(emptyArticleInput($title, $summary, $content) !== false) {
            header('location:../editarticle.php?error=emptyinput');
            exit();
        }
        //Passing new variables to users table
        editArticle($connect, $title, $summary, $content, $id);
    }
    else {
        //Redirecting to editprofile page if form was not submitted
        header('location:../editarticle.php');
    }