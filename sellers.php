<?php include_once "header.php";
require_once('inc/functions-include.php');
require_once('inc/database-include.php');
 ?>

<div class="container">
    <?php
        if(isset($_SESSION['name'])) {
            if(!isset($_POST['filters'])) {//Showing Add filters button if it was not pressed yet
                echo '<form method="POST" action="../sellers.php"><button class="button" name="filters" type="submit">Add filters</button></form>';
            }
                    if(isset($_POST['filters'])) {//If filters button was pressed showing box to enter postcode
                        echo '<div class="selection-box"><br/>
                                <label for="pcode">Enter your full postcode or at least first three symbols of your postcode:</label><br/><br/>
                                <form method="POST" action="../sellers.php">
                                    <input type="text" name="pcode"><br/>
                                    <button class="button" name="psubmit" type="submit">Search</button>
                                </form>
                        </div>';
                    }
                    if(isset($_POST['psubmit']) && strlen($_POST['pcode']) >= 3) { //If postcode is longer than 3 or equal to it starting query
                        echo '<div class="selection-box">';
                        $sql = "SELECT * FROM postcodelatlng WHERE postcode LIKE ?"; //getting postcodes from database that would match the one was entered
                            
                        $stmt = mysqli_stmt_init($connect);
                            
                        if(!mysqli_stmt_prepare($stmt, $sql)) {
                            header('location:../sellers.php?error=stmtfailed');
                            exit();
                        }
                        $pcode = '%'.$_POST['pcode'].'%'; //Setting postcode variable to be passed to database for request
                        mysqli_stmt_bind_param($stmt, "s", $pcode);  
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $resultCheck = mysqli_num_rows($result);
                            
                        if($resultCheck > 0) {
                            echo '
                            <form method="POST" action="../sellers.php">
                                <label for="postcode">Select your postcode by which results will be filtered</label>
                                <select name="postcode">';

                                while($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="'.$row['postcode'].'">'.$row['postcode'].'</option>';//Dropdown menu for postcode selection
                                }

                                echo '
                                </select>';
                        } else if($resultCheck < 1){//Error message if no postcodes where found by the postcode that was entered
                            echo '<div class="error-msg">No postcode found, please check if you have entered a correct postcode or try to enter different postcode</div>';
                        }
                                        echo  '
                                        <label for="distance">Select range</label>
                                            <select name="distance" id="distance">
                                                <option value=5>5 miles</option>
                                                <option value=10>10 miles</option>
                                                <option value=50>50 miles</option>
                                                <option value=100>100 miles</option>
                                                <option value=200>200 miles</option>
                                                <option value=300>300 miles</option>
                                                <option value=500>500 miles</option>
                                                <option value=99999>Any</option>
                                            </select>
                                        <label for="limit">Select result limit</label>
                                            <select name="limit" id="limit">
                                                <option value=1>1 results</option>
                                                <option value=3>3 results</option>
                                                <option value=5>5 results</option>
                                                <option value=10>10 results</option>
                                                <option value=10>20 results</option>
                                                <option value=50>50 results</option>
                                                <option value=99999>All</option>
                                            </select>
                                        <button name="submit">Search</button>
                                    </form>
                                </div>';
                    }
                $sql = "SELECT longitude, latitude FROM postcodelatlng WHERE postcode = ?"; //getting longitude and latitude by postcode from database
                $stmt = mysqli_stmt_init($connect);
                if(!mysqli_stmt_prepare($stmt, $sql)) {
                    header('location:../sellers.php?error=stmtfailed');
                    exit();
                }
                //By the choice of user setting the postcode variable that is going to be used for query
                if(isset($_POST['submit'])){
                    $pcode = $_POST['postcode'];
                } else {
                    $pcode = $_SESSION['postcode'];
                }
                mysqli_stmt_bind_param($stmt, "s", $pcode);  
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $resultCheck = mysqli_num_rows($result);
                if($resultCheck > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $longitude = $row['longitude']; //Assigning longitude and latitude variables to be used later
                    $latitude = $row['latitude'];
                }
                if(isset($_POST['submit'])){
                    $limit = $_POST['limit'];
                    $insert = $_POST['distance'];
                }
                else {
                    $insert = 99999;
                    $limit = 800;
                }
                //Formula inserted to calculate distance between the postcode used and the shop and showing the results from closest to the farest
                $sql = "SELECT *, ( 3959 * acos( cos( radians(?) ) * cos( radians( latitude ) ) 
                * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin(radians(latitude)) ) ) AS distance FROM shops WHERE shopType = ? HAVING distance <= ? ORDER BY distance LIMIT ?";
                $stmt = mysqli_stmt_init($connect);
                //Checking if there are no mistakes in statements
                if(!mysqli_stmt_prepare($stmt, $sql)) {
                    header('location:../sellers.php?error=stmtfailed');
                    exit();
                }
                $insert2 = 'seller';
                mysqli_stmt_bind_param($stmt, "ssssss", $latitude, $longitude, $latitude, $insert2, $insert, $limit);  
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $resultCheck = mysqli_num_rows($result);

                if($resultCheck > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo '                     
                                    <div class="shoptitle">'.$row['shopName'].'
                                    <span class="review-count">
                                        <div style="color:red">-'.$row['negRev'].'</div>
                                    </span>
                                    <span class="review-count">
                                        <div style="color:green">+'.$row['posRev'].'</div>
                                    </span>
                                    <span class="review-button">
                                        <form method="POST" action="../reviews.php">
                                            <input name="id" type="hidden" value="'.$row['id'].'">
                                            <input name="name" type="hidden" value="'.$row['shopName'].'">
                                            <button type="submit" name="read">Read reviews</button>
                                        </form>
                                    </span>
                                    <span class="review-button">
                                        <form method="POST" action="../writereview.php">
                                            <input name="id" type="hidden" value="'.$row['id'].'">
                                            <input name="name" type="hidden" value="'.$row['shopName'].'">
                                            <button type="submit" name="review">Write a review</button>
                                        </form>
                                    </span>
                                    <span class="shop-message">
                                    <form method="POST" action="../shopmessages.php">
                                        <input name="id" type="hidden" value="'.$row['id'].'">
                                        <input name="name" type="hidden" value="'.$row['shopName'].'">
                                        <button type="submit" name="message">Send a message</button>
                                    </form>
                                    </span>
                                </div>
                                <div class="shopcontent"><b>'.$row['shopDescription'].'</b></div>
                                <div class="shopfooter">
                                    Distance: ~<b>' .round($row['distance']).'miles</b> |
                                    Address: <b>'.$row['houseNumber'].' '.$row['shopAddress'].', '.$row['town'].', '.$row['postcode'].'</b>
                                </div>';
                    }
                } else if($resultCheck == 0) { //if there are no results showing error message
                    echo '<div class="error-msg">No results found</div>';
                }
                mysqli_stmt_close($stmt);
        } else {
            echo '<div class="error-msg">You must be registered to access this page</div>';
        }
    ?>
    <?php echo '<br/><b>Results showing for: '.$pcode.'</b>';
//Show error messages if any are there
if(isset($_GET['error'])) {
    if($_GET['error'] == 'stmtfailed') {
        echo "<div class='error-msg'>Something went wrong!</div>";
    }
}   ?>
</div>
   
<?php include_once "footer.php" ?>
