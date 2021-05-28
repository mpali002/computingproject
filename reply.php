<?php include_once "header.php";
require_once('inc/functions-include.php');
require_once('inc/database-include.php');
 ?>

<div class="container">

<?php
    if(isset($_POST['reply'])) {

        echo'
            <div class="selection-box">
                <center><b>Reply to message from: '.$_POST['customerName'].'</b></center><br/>
                <form method="POST" action="../inc/message-include.php">
                    <div class="message-input">
                        <input type="hidden" name="customerId" value="'.$_POST['customerId'].'"> 
                        <input type="hidden" name="customerName" value="'.$_POST['customerName'].'">
                        <input type="hidden" name="shopName" value="'.$_POST['shopName'].'">
                        <input type="hidden" name="messageId" value="'.$_POST['messageId'].'">
                        <input class="m-input" type="text" name="message">
                        <button type="submit" name="send">Reply</button>
                    </div>
                </form>
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
