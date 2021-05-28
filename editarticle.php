<?php 
include_once "header.php";

require_once('inc/functions-include.php');
require_once('inc/database-include.php');

if(isset($_GET['editid'])){
    $id = $_GET['editid'];
}
?>

<div class="container">
        <?php //Getting article from database with mysqli query
        if(isset($_GET['editid']) && isset($_SESSION['admin'])){
            $sql = "SELECT * FROM articles WHERE id = ?;";
            $stmt = mysqli_stmt_init($connect);
            //Checking if there are no mistakes in statements
            if(!mysqli_stmt_prepare($stmt, $sql)) {
                header('location:/editarticle.php?error=stmtfailed');
                exit();
            }
            mysqli_stmt_bind_param($stmt, "s", $id);  
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $resultCheck = mysqli_num_rows($result);
            //Showing user information below
            if($resultCheck > 0) {
                $row = mysqli_fetch_assoc($result);
                echo '<form method="POST" action="../inc/edit-article-include.php">
                        <div class="createarticle">
                            <h1>Edit article</h1>
                            <input type="text" name="title" value="'.$row['title'].'"><br/>
                            <textarea name="summary">'.$row['summary'].'</textarea><br/>
                            <textarea name="content">'.$row['content'].'</textarea>
                            <input type="hidden" name="id" value="'.$row['id'].'">
                            <button class="button" type="submit" name="edit">Post</button>
                        </div>
                     </form>';
            }
            mysqli_stmt_close($stmt);
        } else {
            echo '<div class="error-msg">You are not an admin!</a>';
        }
        ?>
        
        </div>
        </div>
    <?php
        //Show error messages if any are there
        if(isset($_GET['error'])) {
            if($_GET['error'] == 'emptyinput') {
                echo "<div class='error-msg'>Fill in all required fields!</div>";
            }
            else if($_GET['error'] == 'stmtfailed') {
                echo "<div class='error-msg'>Something went wrong!</div>";
            }
        }
        ?>
</div>
   
<?php include_once "footer.php" ?>
