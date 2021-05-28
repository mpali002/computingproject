<?php
    session_start();
    if(isset($_GET['deleteid'])) {
        //Creating variable for delete id
        $delete_id = $_GET['deleteid'];

        //Calling functions and database includes
        require_once('functions-include.php');
        require_once('database-include.php');
        //Deleting article
        deleteArticle($connect, $delete_id);
    }
    else {
        //Redirecting back to index page if delete button was not pressed
        header('location:../index.php');
    }