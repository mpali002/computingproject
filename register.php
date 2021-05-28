<?php include_once "header.php";
require_once('inc/functions-include.php');
require_once('inc/database-include.php');
 ?>

<!-- Creating register form and adding some styling to it -->
<div class="container" style="width:30%">
                <div class="selection-box"><br/>
                        Enter your full postcode or at least first three symbols of your postcode:<br/><br/>
                            <form method="POST" action="../register.php">
                                <input type="text" name="pcode"><br/>
                                <button class="button" name="psubmit" type="submit">Search</button>
                            </form>
                </div>

        <?php 
        if(isset($_POST['psubmit']) && strlen($_POST['pcode']) >= 3) {  //if entered postcode is longer or equal to 3 running the code below
            $sql = "SELECT * FROM postcodelatlng WHERE postcode LIKE ?";

            $stmt = mysqli_stmt_init($connect);

            if(!mysqli_stmt_prepare($stmt, $sql)) {
                header('location:../register.php?error=stmtfailed');
                exit();
            }
            $pcode = '%'.$_POST['pcode'].'%';
            mysqli_stmt_bind_param($stmt, "s", $pcode);  
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $resultCheck = mysqli_num_rows($result);

            if($resultCheck > 0) {
                echo'
                <form method="POST" action="../inc/register-include.php">
                    <div class="reg-form">
                    <h1>Please fill in the form to register</h1>
                        <div class="reg-inputs">
                            <input type="text" name="firstname" placeholder="First Name (Optional)">
                            <input type="text" name="lastname" placeholder="Last Name (Optional)">
                            <input type="text" name="uname" placeholder="Username">
                            <input type="text" name="email" placeholder="E-mail address">
                            <input type="password" name="pw" placeholder="Password">
                            <input type="password" name="pw-rpt" placeholder="Password repeat">
                        <div class="selection-box">
                            <label for="postcode">Select your postcode:</label>
                            <select name="postcode">';

                            while($row = mysqli_fetch_assoc($result)) { //Dropdown menu for postcode selection
                                echo '<option value="'.$row['postcode'].'">'.$row['postcode'].'</option>';
                            }
                        echo '</select>
                        </div>
                        <div class="selection-box">
                                <p>Please select your account type:</p>
                                <input type="radio" id="shop" name="acctype" value="shop">
                                <label for="shop">Shop</label>
                                <input type="radio" id="customer" name="acctype" value="customer">
                                <label for="customer">Customer</label>
                        </div>
                        <button class="button" type="submit" name="submit">Register</button>
                    </div>
                </form>';
            } else if($resultCheck < 1){ //If there are no results showing an error message
                echo '<div class="error-msg">No postcode found, please check if you have entered a correct postcode or try to enter different postcode</div>';
            }
            mysqli_stmt_close($stmt);
        }
        //Show error messages if any are there
        if(isset($_GET['error'])) {
            if($_GET['error'] == 'emptyinput') {
                echo "<div class='error-msg'>Fill in all required fields!</div>";
            }
            else if($_GET['error'] == 'invaliduname') {
                echo "<div class='error-msg'>Invalid username</div>";
            }
            else if($_GET['error'] == 'invalidemail') {
                echo "<div class='error-msg'>Please use a valid E-Mail address!</div>";
            }
            else if($_GET['error'] == 'pwmatch') {
                echo "<div class='error-msg'>Passwords doesn't match!</div>";
            }
            else if($_GET['error'] == 'unametaken') {
                echo "<div class='error-msg'>This username or e-mail is already being used!</div>";
            }
        }
        ?>
</div>

<?php include_once "footer.php" ?>