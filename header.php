<?php session_start(); ?>
<?php Header("Cache-Control: max-age=3000, must-revalidate");?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!DOCTYPE html>
<html lang="en">

<meta charset="utf-8">
<!--Including stylesheet-->
<link rel="stylesheet" href="style.css">

<body>
    <ul>
    <?php
        if(isset($_SESSION['admin'])) { //Showing user name if there is a session started
            echo '<li style="float:right;font-family:verdana;font-size:12px;margin-right:20px;"><span id="admin">Admin</span></li>';
        } 
        if(isset($_SESSION['name'])) { //Showing user name if there is a session started
            echo '<li style="float:right;font-family:verdana;font-size:12px;margin-right:10px;">Logged in as: <span id="name">' . $_SESSION['name'] . '</span></li>';
        }
    ?>
        <li><img src="../img/logo.png"></li> <!--Page logo-->
        <!--Page navigation-->
        <li><a href="../index.php">Home</a></li>
        <li><a href="../about.php">About</a></li>
            <?php 
                if(isset($_SESSION['name'])) { //Shown only if user is logged in
                    echo "<li><a href='../profile.php'>My Profile</a></li>";
                    if($_SESSION['acctype'] == 'shop'){ //If user account is shop shown this button
                        echo "<li><a href='../shopeditor.php'>Shop Editor</a></li>";
                        echo "<li><a href='../messages.php'>Messages</a></li>";
                    }
                    else if($_SESSION['acctype'] == 'customer'){ //If user account type is customer shown buttons below
                        echo "<li><a href='../shops.php'>Repair Shops</a></li>";
                        echo "<li><a href='../sellers.php'>Sellers</a></li>";
                    }
                    echo "<li><a href='../inc/logout.php'>Log out</a></li>";
                }
                else { //When user is not logged in showing register and log in buttons
                    echo "<li><a href='../register.php'>Register</a></li>";
                    echo "<li><a href='../login.php'>Log In</a></li>";
                }
            ?>
    </ul>