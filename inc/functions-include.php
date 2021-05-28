<?php

//--------------------------------------
//---------REGISTER FUNCTIONS-----------
//--------------------------------------

//Function to check if all fields are filled
function emptyRegisterInput($uname, $email, $pw, $pwRepeat) {
    $result;
    if(empty($uname) or empty($email) or empty($pw) or empty($pwRepeat)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}
//Checks if email field is filled for editprofile.php
function emptyEditInput($email) {
    $result;
    if(empty($email)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}
//Function to check if the names have only the symbols allowed to use
function invalidUname($uname, $fname, $lname) {
    $result;
    if(!preg_match("/^[a-zA-Z0-9]*$/", $uname) or !preg_match("/^[a-zA-Z]*$/", $fname) or !preg_match("/^[a-zA-Z]*$/", $lname)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}
//Checking if email address is valid
function invalidEmail($email) {
    $result;
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}
//Checking if passwords match
function pwMatch($pw, $pwRepeat) {
    $result;
    if($pw !== $pwRepeat) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}
//Checking if the username already exists in the database
function uNameExists($uname, $email, $connect) {
    $sql = "SELECT * FROM users WHERE userName = ? or userEmail = ?;";
    $stmt = mysqli_stmt_init($connect);
    //Checking if there are no mistakes in statements
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header('location:/register.php?error=stmtfailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $uname, $email);  
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        $result = false;
        return $result;
    }
    mysqli_stmt_close($stmt);
}
//Creating new user
function createUser($connect, $fname, $lname, $uname, $email, $acctype, $postcode, $pw) {
    $sql = "INSERT INTO users (firstName, lastName, userName, userEmail, accType, postcode, userPw) VALUES (?,?,?,?,?,?,?);";
    $stmt = mysqli_stmt_init($connect);
    //Checking if there are no mistakes in statements
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header('location:/register.php?error=stmtfailed');
        exit();
    }
    $hashedPw = password_hash($pw, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "sssssss", $fname, $lname, $uname, $email, $acctype, $postcode, $hashedPw);    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('location:../index.php?error=none');
    exit();
}
//Updating users row with new values
function editUser($connect, $fname, $lname, $email, $id) {
    $sql = "UPDATE users SET firstName = ?, lastName = ?, userEmail = ? WHERE userID = ?;";
    $stmt = mysqli_stmt_init($connect);
    //Checking if there are no mistakes in statements
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header('location:/profile.php?error=stmtfailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ssss", $fname, $lname, $email, $id);    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('location:../profile.php?error=none');
    exit();
}

//--------------------------------
//---------LOG IN FUNCTIONS-------
//--------------------------------

//Checking if all input fields are filled
function emptyLoginInput($username, $pw) {
    $result;
    if(empty($username) or empty($pw)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}
//Starting session, logging in user
function loginUser($connect, $username, $pw) {
    $uNameExists = uNameExists($username, $username, $connect);
    
    if($uNameExists === false) {
        header("location:../login.php?error=notexist");
        exit();
    }

    $pwHashed = $uNameExists['userPw'];
    $checkPw = password_verify($pw, $pwHashed);

    if($checkPw === false) {
        header("location: ../login?error=wrongpass");
        exit();
    } else if($checkPw === true) {
        session_start();
        //Setting variables that are going to be used during the session in different pages
        $_SESSION['id'] = $uNameExists['userID'];
        $_SESSION['name'] = $uNameExists['userName'];
        $_SESSION['acctype'] = $uNameExists['accType'];
        $_SESSION['shop'] = $uNameExists['hasShop'];
        $_SESSION['postcode'] = $uNameExists['postcode'];
        $_SESSION['longitude'] = $uNameExists['longitude'];
        $_SESSION['latitude'] = $uNameExists['latitude'];
        if($uNameExists['adminLvl'] > 0) {
            $_SESSION['admin'] = $uNameExists['adminLvl'];
        }
        header("location:../index.php");
        exit();
    }
}

//--------------------------------
//--------ARTICLE FUNCTIONS-------
//--------------------------------

//Checking if there are any empty input fields
function emptyArticleInput($title, $summary, $content) {
    $result;
    if(empty($title) or empty($summary) or empty($content)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}
//Creating new article
function createArticle($connect, $title, $summary, $content, $date) {
    $sql = "INSERT INTO articles (publishedDate, title, summary, content) VALUES ('$date',?,?,?);";
    $stmt = mysqli_stmt_init($connect);
    //Checking if there are no mistakes in statements
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header('location:/createarticle.php?error=stmtfailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "sss", $title, $summary, $content);    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('location:../index.php');
    exit();
}
function editArticle($connect, $title, $summary, $content, $id) {
    $sql = "UPDATE articles SET title = ?, summary = ?, content = ? WHERE id = ?;";
    $stmt = mysqli_stmt_init($connect);
    //Checking if there are no mistakes in statements
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header('location:/profile.php?error=stmtfailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ssss", $title, $summary, $content, $id);    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('location:../index.php');
    exit();
}
//Deleting article
function deleteArticle($connect, $delete_id) {
        $sql = 'DELETE FROM articles WHERE id = ?';
        $stmt = mysqli_stmt_init($connect);
        
        //Checking if there are no mistakes in statements
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header('location:/index.php?error=stmtfailed');
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $delete_id);    
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    
        header('location:../index.php');
        exit();
}

//--------------------------------
//---------SHOP FUNCTIONS---------
//--------------------------------

//Creating new shop
function createShop($connect, $name, $description, $address, $number, $city, $pcode, $longitude, $latitude, $type, $id){
    $sql = "INSERT INTO shops (shopName, shopDescription, shopAddress, houseNumber, town, postcode, longitude, latitude, shopType, ownerId) VALUES (?,?,?,?,?,?,?,?,?,?);";
    $stmt = mysqli_stmt_init($connect);
    //Checking if there are no mistakes in statements
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header('location:../shopeditor.php?error=stmtfailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ssssssssss", $name, $description, $address, $number, $city, $pcode, $longitude, $latitude, $type, $id);    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('location:../shopeditor.php');
    exit();    
}

//Checking if there are any empty input fields
function emptyShopInput($name, $description, $address, $number, $city){
    $result;
    if(empty($name) or empty($description) or empty($address) or empty($number) or empty($city)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}
//Creating new review for shop
function createReview($connect, $rName, $type, $comment, $shopId, $revId, $date, $time) {
    if($type == 'pos'){
        $sql = "UPDATE shops SET posRev = posRev + 1 WHERE id = ?";
    } else if($type == 'neg') {
        $sql = "UPDATE shops SET negRev = negRev + 1 WHERE id = ?";
    }
    $stmt = mysqli_stmt_init($connect);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header('location:../writereview.php?error=stmtfailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $shopId);  
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $sql = "INSERT INTO reviews (rName, rType, comment, shopId, revId, reviewDate, reviewTime) VALUES (?,?,?,?,?,?,?);";
    $stmt = mysqli_stmt_init($connect);
    //Checking if there are no mistakes in statements
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header('location:../writereview.php?error=stmtfailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "sssssss", $rName, $type, $comment, $shopId, $revId, $date, $time);    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('location:../index.php');
    exit();  
}
//Checking if there are empty input fiels in review
function emptyReviewInput($comment) {
    $result;
    if(empty($comment)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}
//Shop message functions
function sendMessage($connect, $cName, $message, $from, $to, $date, $time, $shopName) {

    $sql = "INSERT INTO messages (customerName, cMessage, mFrom, mTo, cDate, cTime, shopName) VALUES (?,?,?,?,?,?,?);";
    $stmt = mysqli_stmt_init($connect);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header('location:../shopmessages.php?error=stmtfailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "sssssss", $cName, $message, $from, $to, $date, $time, $shopName);  
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('location:../index.php');
    exit();  

}
function emptyMessageInput($message) {
    $result;
    if(empty($message)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}
function setRead($connect, $messageId) {
    $sql = "UPDATE messages SET replied = 1 WHERE id = ?";
    $stmt = mysqli_stmt_init($connect);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header('location:../reply.php?error=stmtfailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $messageId);  
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return;
}