<?php include_once "header.php";
require_once('inc/functions-include.php');
require_once('inc/database-include.php');
 ?>

<div class="container">
    <?php
        if(isset($_SESSION['name']) && $_SESSION['acctype'] == 'shop') {
                $sql = "SELECT * FROM shops WHERE ownerId = ?;";
                $stmt = mysqli_stmt_init($connect);
                //Checking if there are no mistakes in statements
                if(!mysqli_stmt_prepare($stmt, $sql)) {
                    header('location:/shopeditor.php?error=stmtfailed');
                    exit();
                }
                mysqli_stmt_bind_param($stmt, "s", $_SESSION['id']);  
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $resultCheck = mysqli_num_rows($result);

                if($resultCheck > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="shoptitle">'.$row['shopName'].'</div>
                        <div class="shopcontent"><b>'.$row['shopDescription'].'</b></div>
                        <div class="shopfooter">
                            Address: <b>'.$row['houseNumber'].' '.$row['shopAddress'].', '.$row['town'].', '.$row['postcode'].'</b>
                        </div>';
                    }
                }
                else {
                    echo '<div style="text-align:center;">
                            <a class="button" href="../createshop.php">Add your repair shop</a>
                        </div>';
                }
                mysqli_stmt_close($stmt);

        } else {
            echo '<div class="error-msg">You must be registered to access this page</div>';
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
