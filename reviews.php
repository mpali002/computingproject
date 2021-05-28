<?php include_once "header.php";
require_once('inc/functions-include.php');
require_once('inc/database-include.php');
 ?>

<div class="container">
    <?php
        if(isset($_POST['read']) && isset($_SESSION['id'])){ //If Read reviews button was pressed and user is logged in receiving reviews about shop
            $id = $_POST['id'];
                $sql = "SELECT * FROM reviews WHERE shopId = ? ORDER BY reviewDate, reviewTime"; //Searching for reviews by shopid
                $stmt = mysqli_stmt_init($connect);
                //Checking if there are no mistakes in statements
                if(!mysqli_stmt_prepare($stmt, $sql)) {
                    header('location:../reviews.php?error=stmtfailed');
                    exit();
                }
                mysqli_stmt_bind_param($stmt, "s", $id);  
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $resultCheck = mysqli_num_rows($result);
                //Printing reviews
                if($resultCheck > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="review-box2">
                                <div class="review-title">';
                            if($row['rtype'] == 'pos') {
                                echo '<span class="review-type" style="color:green;">
                                        Positive
                                      </span>';
                            } else if($row['rtype'] == 'neg') {
                                echo '<span class="review-type"  style="color:red;">
                                        Negative
                                      </span>';
                            }
                            echo '    <span class="review-date">
                                            '.$row['reviewDate'].' '.$row['reviewTime'].'
                                      </span>
                                      <span class="review-name">
                                            Written by:<b>'.$row['rName'].'</b>
                                      </span>
                                </div>
                                <br/><hr/>
                                <div style="padding:10px;margin-top:20px;">
                                    '.$row['comment'].'
                                </div>
                              </div><br/>';
                    }
                } else { //Message if there are no reviews about the shop
                    echo '<b>There are no reviews about this shop</b>';
                }
                mysqli_stmt_close($stmt);
        } else { //User not logged in
            echo '<div class="error-msg">You must log in</div>';
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