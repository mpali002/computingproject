<?php include_once "header.php" ?>

<div class="container">
    <?php
    //Making page visible only for admin users
    if(isset($_SESSION['admin'])) {
        echo '<form method="POST" action="../inc/create-article-include.php">
                <div class="createarticle">
                    <h1>Create new article</h1>
                    <input type="text" name="title" placeholder="Title"><br/>
                    <textarea name="summary" placeholder="summary"></textarea><br/>
                    <textarea name="content" placeholder="content"></textarea>
                    <button class="button" type="submit" name="submit">Post</button>
                </div>
              </form>';
    } else { //if not admin showing message below
        echo '<div class="error-msg">You are not an admin. Back to <a href="../index.php">home page</a>.</div>';
    }
    //Error handlers below
    if(isset($_GET['error'])) {
        if($_GET['error'] == 'emptyinput') {
            echo "<div class='error-msg'>Fill in all required fields!</div>";
        }
        if($_GET['error'] == 'stmtfailed') {
            echo "<div class='error-msg'>Something went wrong!</div>";
        }
    }
?>
</div>
   
<?php include_once "footer.php" ?>