<?php include_once "header.php" ?>
<!-- Creating log-in form and adding some styling to it -->
<div class="container">
    <form method="POST" action="../inc/login-include.php">
        <div class="login-form">
            <h1>Enter your log in details</h1>
            <div class="login-inputs">
                <input type="text" name="name" placeholder="Username or E-mail">
                <input type="password" name="pw" placeholder="Password">
                <button class="button" type="submit" name="submit">Log In</button>
            </div>
        </div>
    </form>
    <?php
        //Show error messages if any are there
        if(isset($_GET['error'])) {
            if($_GET['error'] == 'emptyinput') {
                echo "<div class='error-msg'>Fill in all fields!</div>";
            }
            else if($_GET['error'] == 'notexist') {
                echo "<div class='error-msg'>User with this e-mail or username doesn't exist</div>";
            }
            else if($_GET['error'] == 'wrongpass') {
                echo "<div class='error-msg'>Incorrect password!</div>";
            }
            else if($_GET['error'] == 'notexist') {
                echo "<div class='error-msg'>User doesn't exist</div>";
            }
        }
        ?>
</div>

<?php include_once "footer.php" ?>