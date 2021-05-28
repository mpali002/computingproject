<?php 
include_once "header.php";

require_once('inc/functions-include.php');
require_once('inc/database-include.php');

?>

<div class="container">
        <form method="POST" action="../inc/editprofile-include.php">
        <?php //Getting user info from database with mysqli query
            $sql = "SELECT * FROM users WHERE userName = ?;";
            $stmt = mysqli_stmt_init($connect);
            //Checking if there are no mistakes in statements
            if(!mysqli_stmt_prepare($stmt, $sql)) {
                header('location:/editprofile.php?error=stmtfailed');
                exit();
            }
            mysqli_stmt_bind_param($stmt, "s", $_SESSION['name']);  
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $resultCheck = mysqli_num_rows($result);
            //Showing user information below
            if($resultCheck > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo 
                    "<div class='wrapper'>
                        <div class='profiletitle'>
                            ".$row['userName']."
                        </div>
                        <div class='profilecontent'> 
                            <div class='profileinputs'>   
                                <input type='text' name='firstname' placeholder=".$row['firstName'].">
                                <input type='text' name='lastname' placeholder=".$row['lastName'].">
                                <input type='text' name='email' placeholder=".$row['userEmail']."><br/><br/>
                                <button class='button' type='submit' name='submit'>Edit</button>
                            </div>
                        </div>
                    </div>";
                }
            }
            mysqli_stmt_close($stmt);
        ?>
        
        </div>
        </div>
        </form>
    <?php
        //Show error messages if any are there
        if(isset($_GET['error'])) {
            if($_GET['error'] == 'emptyinput') {
                echo "<div class='error-msg'>Fill in all required fields!</div>";
            }
            else if($_GET['error'] == 'invalidemail') {
                echo "<div class='error-msg'>Please use a valid E-Mail address!</div>";
            }
            else if($_GET['error'] == 'stmtfailed') {
                echo "<div class='error-msg'>Something went wrong!</div>";
            }
        }
        ?>
</div>
   
<?php include_once "footer.php" ?>
