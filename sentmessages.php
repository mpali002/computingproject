<?php  include_once "header.php";
require_once('inc/functions-include.php');
require_once('inc/database-include.php');
?>
<div class="container">
<?php
        if(isset($_SESSION['id'])) {
                $sql = "SELECT * FROM messages WHERE mFrom = ? ORDER BY cDate, cTime DESC;"; //Searching for messages by shopid and customerId
                $stmt = mysqli_stmt_init($connect);
                //Checking if there are no mistakes in statements
                if(!mysqli_stmt_prepare($stmt, $sql)) {
                    header('location:../sentmessages.php?error=stmtfailed');
                    exit();
                }
                $id = $_SESSION['id'];
                mysqli_stmt_bind_param($stmt, "s", $id);  
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $resultCheck = mysqli_num_rows($result);
                //Printing messages
                if($resultCheck > 0) {
                    echo '<br/><h1>Messages you have sent:</h1><br/>';
                    while($row = mysqli_fetch_assoc($result)) {
                        if($row['mFrom'] == $_SESSION['id']){
                            echo '<div class="message-content2">
                                    <div class="message-top2"><b>'.$row['shopName'].'</b> '.$row['cDate'].' '.$row['cTime'].'</div>
                                    <div class="message-top">To: '.$row['customerName'].'</div>
                                    '.$row['cMessage'].'
                                </div>';
                        }
                    }
                } else { //Message if there are no messages related to the shop
                    echo '</br><br/><center><b>There are no new messages</b></center>';
                }
                mysqli_stmt_close($stmt);
        } else if(!isset($_SESSION['id'])) { //User not logged in or wrong account type
            echo '<div class="error-msg">
                    You must log in or wrong account type
                  </div>';
        }
//Show error messages if any are there
if(isset($_GET['error'])) {
    if($_GET['error'] == 'stmtfailed') {
        echo "<div class='error-msg'>Something went wrong!</div>";
    }
}
    ?>
</div>
<?php include_once "footer.php" ?>