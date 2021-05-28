<?php
    session_start();
    if(isset($_POST['submit'])) {
        //Creating variables for article inputs
        $title = $_POST['title'];
        $summary = $_POST['summary'];
        $content = $_POST['content'];
        $id = $_SESSION['id'];
        $date = date('Y-m-d');

        //Calling functions and database includes
        require_once('functions-include.php');
        require_once('database-include.php');


        //Checks if any inputs are empty
        if(emptyArticleInput($title, $summary, $content) !== false) {
            header('location:../createarticle.php?error=emptyinput');
            exit();
        }
        //creating article
        createArticle($connect, $title, $summary, $content, $date);
    }
    else {
        //Redirecting to page if form wasn't submited
        header('location:../createarticle.php');
    }