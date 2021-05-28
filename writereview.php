<?php include_once "header.php";
require_once('inc/functions-include.php');
require_once('inc/database-include.php');
 ?>

<div class="container">
    <?php
        if(isset($_POST['review']) && isset($_SESSION['id'])){ //If user is logged in and button was pressed showing this form
            $id = $_POST['id'];
            echo '
                <div class="review-box">
                    <h1>Write your review for '.$_POST['name'].'</h1>
                    <form method="POST" action="../inc/review-include.php">
                        <input type="hidden" name="shopId" value="'.$id.'">
                        <select name="type">
                            <option name="pos" value="pos">Positive</option>
                            <option name="neg" value="neg">Negative</option>
                        </select>
                        <textarea name="comment"></textarea><br/><br/>
                        <button name="submit" type="submit" class="button">Submit</button>
                    </form>
                </div>
            ';
        } else { //If user is not logged in showing message below
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