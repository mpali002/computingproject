<?php
    session_start();
    //Calling functions and database includes

    if(isset($_POST['submit'])) {
        //Creating variables for message inputs
        $message = $_POST['message'];
        $to = $_POST['shopId'];
        $from = $_SESSION['id'];
        date_default_timezone_set("Europe/London");
        $date = date('Y-m-d');
        $time = date("H:i:s"); 
        $cName = $_SESSION['name'];
        $shopName = $_POST['shopName'];

        require_once('functions-include.php');
        require_once('database-include.php');

        //Checks if any inputs are empty
        if(emptyMessageInput($message) !== false) {
            header('location:../shopmessages.php?error=emptyinput');
            exit();
        }
        sendMessage($connect, $cName, $message, $from, $to, $date, $time, $shopName);
    }
    else if(isset($_POST['send'])) {
        $message = $_POST['message'];
        $from = $_SESSION['id'];
        $to = $_POST['customerId'];
        date_default_timezone_set("Europe/London");
        $date = date('Y-m-d');
        $time = date("H:i:s"); 
        $cName = $_POST['customerName'];
        $shopName = $_POST['shopName'];
        $messageId = $_POST['messageId'];

        require_once('functions-include.php');
        require_once('database-include.php');

        //Checks if any inputs are empty
        if(emptyMessageInput($message) !== false) {
            header('location:../messages.php?error=emptyinput');
            exit();
        }
        setRead($connect, $messageId);
        sendMessage($connect, $cName, $message, $from, $to, $date, $time, $shopName);

    } else {
        header('location:../index.php');
    }