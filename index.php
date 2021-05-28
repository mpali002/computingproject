<?php include_once "header.php"; ?>
<?php
require_once('inc/functions-include.php');
require_once('inc/database-include.php');
?>

<div class="container">
    <?php
    //Congratulations message when registered
    if(isset($_GET['error'])) {
        if($_GET['error'] == 'none'){
            echo '<div class="success">Congratulations, you are now registered! <a class="button" href="../login.php">Log in</a></div><br/><br/>';
        }
    }    
    ?>
    <!--Greeting-->
    <div id="greeting">
        <b>WELCOME TO OUR WEBSITE</b><br/>
        <?php 
            if(!isset($_SESSION['name'])) {
                echo '<br/>We are going to help you to find best smart device repair shops and sellers. Please <a href="../login.php">Log In</a> or <a href="../register.php">Register</a> to start!</a>';
            } else if($_SESSION['acctype'] == 'shop'){
                echo '<br/>We hope you are enjoying using our services!';
            } else if($_SESSION['acctype'] == 'customer'){
                echo '<br/>We are going to help you to find best smart device repair shops and sellers. Go to one of our shop lists to start!';
            }
        ?>
        <br/>
    </div>
<h1>News</h1>
<!--News below-->
<?php
//Getting 5 latest articles and printing them to the front page
    $sql = "SELECT * FROM articles ORDER by id DESC LIMIT 5;";
    $result = mysqli_query($connect, $sql);
    $resultCheck = mysqli_num_rows($result);

    if($resultCheck > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo '<div class="articletitle">'.$row['title'].'</div>
            <div class="articlecontent"><b>'.$row['summary'].'</b></br></br>'
            .$row['content'].'</div>
            <div class="articlefooter">
                Posted: <b>'.$row['publishedDate'].'</b>';
        //Adding delete button for admin
        if(isset($_SESSION['admin'])) {
            echo "<a id='deletebutton' href='../inc/delete-article-include.php?deleteid=".$row['id']."'>Delete</a>";
            echo "<a id='editbutton' href='../editarticle.php?editid=".$row['id']."'>Edit</a>";
        }
        echo '</div>';
        }
    }
    //Adding new article button for admin
    if(isset($_SESSION['admin'])) {
        echo '<br/><a class="button" href="../createarticle.php">New article</a>';
    } else {
        echo '';
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
