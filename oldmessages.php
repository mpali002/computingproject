<?php  include_once "header.php";
require_once('inc/functions-include.php');
require_once('inc/database-include.php');
?>
<div class="container">
<?php
        if(isset($_SESSION['id'])) {
                $sql = "SELECT * FROM messages WHERE mTo = ? AND replied = ? ORDER BY cDate, cTime DESC;"; //Searching for messages by shopid and customerId
                $stmt = mysqli_stmt_init($connect);
                //Checking if there are no mistakes in statements
                if(!mysqli_stmt_prepare($stmt, $sql)) {
                    header('location:../sentmessages.php?error=stmtfailed');
                    exit();
                }
                $id = $_SESSION['id'];
                $insert = 1;
                mysqli_stmt_bind_param($stmt, "ss", $id, $insert);  
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $resultCheck = mysqli_num_rows($result);
                //Printing messages
                if($resultCheck > 0) {
                    echo '<br/><h1>Messages you already replied to:</h1><br/>';
                    while($row = mysqli_fetch_assoc($result)) {
                        if($row['mTo'] == $_SESSION['id']){
                            echo '<div class="message-content">
                                    <div class="message-top"><b>'.$row['customerName'].'</b> '.$row['cDate'].' '.$row['cTime'].'</div>
                                    <div class="message-top2">To: '.$row['shopName'].'</div>
                                    '.$row['cMessage'].'
                               
                                    <form method="POST" action="../reply.php">
                                        <input type="hidden" name="customerId" value="'.$row['mFrom'].'"> 
                                        <input type="hidden" name="customerName" value="'.$row['customerName'].'">
                                        <input type="hidden" name="shopName" value="'.$row['shopName'].'">
                                        <input type="hidden" name="messageId" value="'.$row['id'].'">
                                        <button type="submit" class="message-button" name="reply">Reply</button>
                                    </form>
                                  </div>';
                        }
                    }
                } else { //Message if there are no messages related to the shop
                    echo '</br><br/><center><b>There are no old messages</b></center>';
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