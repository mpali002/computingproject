<?php include_once "header.php";
require_once('inc/functions-include.php');
require_once('inc/database-include.php');
if(isset($_POST['id'])){
    $shopId = $_POST['id'];
    $shopName = $_POST['name'];
} else if(isset($_GET['shopid'])) {
    $shopId = $_GET['shopid'];
    $shopName = $_GET['shopname'];
}
 ?>

<div class="container">
 
    <?php
        if(isset($_POST['message']) || isset($_GET['shopid']) && isset($_SESSION['id'])){ //If message button was pressed and user is logged in receiving messages associated to shop
    echo '  <form method="POST" action="../inc/message-include.php">
                <div class="message-input">
                    <input type="hidden" name="shopId" value="'.$shopId.'"> 
                    <input type="hidden" name="shopName" value="'.$shopName.'">
                    <input type="text" class="m-input" name="message">
                    <button class="button" name="submit">Send</button>
                </div>
            <br/><br/>
            </form>';
               /* if(isset($_POST['id'])){
                    $id = $_POST['id'];
                } else {
                    $id = $_GET['shopid'];
                }*/
                $sql = "SELECT * FROM messages WHERE (mTo = ? AND mFrom = ?) OR (mFrom = ? AND mTo = ?) ORDER BY cDate, cTime DESC;"; //Searching for messages by shopid and customerId
                $stmt = mysqli_stmt_init($connect);
                //Checking if there are no mistakes in statements
                if(!mysqli_stmt_prepare($stmt, $sql)) {
                    header('location:../shopmessages.php?error=stmtfailed');
                    exit();
                }
                $cId = $_SESSION['id'];
                mysqli_stmt_bind_param($stmt, "ssss", $shopId, $cId, $shopId, $cId);  
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $resultCheck = mysqli_num_rows($result);
                //Printing messages
                if($resultCheck > 0) {
                    echo '<br/><h1>Messages:</h1><br/>';
                    while($row = mysqli_fetch_assoc($result)) {
                        if($row['mFrom'] == $_SESSION['id']){
                            echo '<div class="message-content">
                                    <div class="message-top"><b>'.$row['customerName'].'</b> '.$row['cDate'].' '.$row['cTime'].'</div>
                                    '.$row['cMessage'].'
                                </div>';
                        } else {
                            echo '<div class="message-content2">
                                    <div class="message-top2"><b>'.$row['shopName'].'</b> '.$row['cDate'].' '.$row['cTime'].'</div>
                                    '.$row['cMessage'].'
                                </div>';
                        }
                    }
                
                } else { //Message if there are no messages related to the shop
                    echo '</br><br/><center><b>There are no messages sent to/from this shop</b></center>';
                }
                mysqli_stmt_close($stmt);
        } else if(!isset($_SESSION['id'])) { //User not logged in
            echo '<div class="error-msg">
                    You must log in
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