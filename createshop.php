<?php include_once "header.php";
require_once('inc/functions-include.php');
require_once('inc/database-include.php');
 ?>

<div class="container">
    <?php
        //Showing content only for logged in users
        if(isset($_SESSION['name'])) {
            if(!isset($_POST['mypc']) && !isset($_POST['selectpc']) && !isset($_POST['psubmit'])){ //showing selections when just entered the page
                echo '
                    <div style="display:block;text-align:center;">
                        <form action="../createshop.php" method="POST">
                            <button class="button" type="submit" name="mypc">Use my postcode</button>
                        </form><br/>
                        <form action="../createshop.php" method="POST">
                            <button class="button" type="submit" name="selectpc">Enter different postcode</button>
                        </form>
                    </div>';
            }
            if(isset($_POST['selectpc'])){ //If selected to enter post code showing box below
                    echo '
                    <div class="selection-box"><br/>
                    <label for="pcode">Enter your full postcode or at least first three symbols of your postcode:</label><br/><br/>

                        <form method="POST" action="../createshop.php">
                            <input type="text" name="pcode"><br/>
                            <button class="button" name="psubmit" type="submit">Search</button>
                        </form>';
            }
            if(isset($_POST['psubmit']) || isset($_POST['mypc'])){ //Showing box below only if a choice was made before for postcode
                if($_SESSION['shop'] == 1 || $_SESSION['acctype'] == 'customer'){ //Button is shown only if user account type is 'customer' and he doesn't have any shops yet
                    echo '<div class="error-msg">Wrong account type or you already have a shop</div>';
                }
                else { //If user has a shop running code below
                    echo '        
                        <form method="POST" action="../inc/create-shop-include.php">
                            <div class="reg-form">
                            <h1>Please fill in the following to create your shop</h1>
                            <div class="reg-inputs">
                                <input type="text" name="name" placeholder="Shop name">
                                <textarea type="text" name="description" placeholder="Describe your shop (what services are you doing selling/repairing smart devices)"></textarea>
                                <input type="text" name="address" placeholder="Address">
                                <input type="number" name="number" placeholder="House/flat number">
                                <input type="text" name="city" placeholder="City/Town">';
                        if(isset($_POST['psubmit'])) { //When postcode is entered box with postcode appears. Doesn't appear if profile postcode was used.
                          echo '<br/>
                                <label for="postcode">Select your postcode:</label>
                                <select name="postcode">';
                                    $sql = "SELECT * FROM postcodelatlng WHERE postcode LIKE ?";

                                    $stmt = mysqli_stmt_init($connect);
                        
                                    if(!mysqli_stmt_prepare($stmt, $sql)) {
                                        header('location:../createshop.php?error=stmtfailed');
                                        exit();
                                    }
                                    $pcode = '%'.$_POST['pcode'].'%';
                                    mysqli_stmt_bind_param($stmt, "s", $pcode);  
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);
                                    $resultCheck = mysqli_num_rows($result);
                        
                                    if($resultCheck > 0) {
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="'.$row['postcode'].'">'.$row['postcode'].'</option>';
                                        }
                                    }
                                    mysqli_stmt_close($stmt);
                        echo'   </select>';  
                        } else if(isset($_POST['mypc'])){ //Using profile postcode
                            $sql = "SELECT postcode FROM users WHERE userID LIKE ?";

                            $stmt = mysqli_stmt_init($connect);
                
                            if(!mysqli_stmt_prepare($stmt, $sql)) {
                                header('location:../createshop.php?error=stmtfailed');
                                exit();
                            }
                            $insert = $_SESSION['id'];
                            mysqli_stmt_bind_param($stmt, "s", $insert);  
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $resultCheck = mysqli_num_rows($result);
                
                            if($resultCheck > 0) {
                                $row = mysqli_fetch_assoc($result);
                                    echo '<input name="postcode" type="hidden" value="'.$row['postcode'].'">';//Creates a value to pass to other page
                                    echo '<br/><center>Shop postcode will be set as: <b>'.$row['postcode'].'</b></center>';
                            }
                            mysqli_stmt_close($stmt);
                        }
                    echo '      
                                </div>
                                    <div class="selection-box">
                                        <p>Please select your shop type:</p>
                                        <input type="radio" id="repair" name="type" value="repair">
                                        <label for="repair">Repair shop</label>
                                        <input type="radio" id="seller" name="type" value="seller">
                                        <label for="seller">Device seller</label>
                                    </div>
                                <button class="button" type="submit" name="submit">Create</button>
                            </div>
                        </form>';
                }
            }
        } else { //Showing a message if user is not logged in
            echo '<div class="error-msg">You must log in to access this page</div>';
        }
        //Error handlers below
        if(isset($_GET['error'])) {
            if($_GET['error'] == 'emptyinput'){
                echo '<div class="error-msg">Fill in all fields!</div>';
            }
            else if($_GET['error'] == 'stmtfailed') {
                echo "<div class='error-msg'>Something went wrong!</div>";
            }
     }
    ?>
</div>
   
<?php include_once "footer.php" ?>