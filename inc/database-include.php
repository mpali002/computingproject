<?php

//Setting up variables for database log-in

$serverName = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "cproject";

//Connecting to database

$connect = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);

//Error handler

if(!$connect) {
    die("The connection has failed. Error: " . mysqli_connect_error());
}
