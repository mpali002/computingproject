<?php
    session_start();
    if(isset($_POST['submit'])) {
        //Creating variables for shop inputs
        $name = $_POST['name'];
        $description = $_POST['description'];
        $address = $_POST['address'];
        $number = $_POST['number'];
        $city = $_POST['city'];
        $pcode = $_POST['postcode'];
        $type = $_POST['type'];
        $id = $_SESSION['id'];

        //Calling functions and database includes
        require_once('functions-include.php');
        require_once('database-include.php');
        //Getting longitude and latitude of the postcode through mysqli query
        $sql = "SELECT * FROM postcodelatlng WHERE postcode LIKE ?";

        $stmt = mysqli_stmt_init($connect);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header('location:../createshop.php?error=stmtfailed');
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $pcode);  
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $resultCheck = mysqli_num_rows($result);

        if($resultCheck > 0) {
            $row = mysqli_fetch_assoc($result);
            $longitude = $row['longitude'];
            $latitude = $row['latitude'];
        }

        //Checks if any inputs are empty
        if(emptyShopInput($name, $description, $address, $number, $city) !== false) {
            header('location:../createshop.php?error=emptyinput');
            exit();
        }
        //Creating the shop
        createShop($connect, $name, $description, $address, $number, $city, $pcode, $longitude, $latitude, $type, $id);
    }
    else {
        //Redirecting back to page if form wasn't submited
        header('location:../createshop.php');
    }