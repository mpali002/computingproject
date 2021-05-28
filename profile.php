<?php 
include_once "header.php";

require_once('inc/functions-include.php');
require_once('inc/database-include.php');

?>

<div class="container">
<?php
    $sql = "SELECT * FROM users WHERE userName = ?;";
    $stmt = mysqli_stmt_init($connect);
    //Checking if there are no mistakes in statements
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header('location:/profile.php?error=stmtfailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $_SESSION['name']);  
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resultCheck = mysqli_num_rows($result);


    if($resultCheck > 0) {
        while($row = mysqli_fetch_assoc($result)) { //Getting info that will be available to edit
            echo 
            "
            <div class='wrapper'>
                <div class='profiletitle'>
                    Username: <b>".$row['userName']."</b>
                </div>
                <div class='profilecontent'>
                    First name: <b>".$row['firstName']."</b>
                    <br/>
                    Last name: <b>".$row['lastName']."</b>
                    </br>
                    E-Mail address: <b>".$row['userEmail']."</b>
                    <br/><br/>

                    <a class='button' href='../editprofile.php'>Edit Profile</a>
                </div>
            </div>";
            }
    }
    mysqli_stmt_close($stmt);

//Error handlers
if(isset($_GET['error'])) {
        if($_GET['error'] == 'none'){
            echo '<div class="success">Profile successfully updated!</div>';
        }
        else if($_GET['error'] == 'stmtfailed') {
            echo "<div class='error-msg'>Something went wrong!</div>";
        }
 }    
?>
</div>
   
<?php include_once "footer.php" ?>
