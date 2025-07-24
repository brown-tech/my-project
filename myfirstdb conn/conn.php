<?php
//Set database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myfirstdbconnection";

//connect using mySQL
$conn = new mysqli($servername, $username, $password, $dbname);

//check the connection
if($conn -> connect_error){
    die("connection failed:". $conn->connect_error);
}
echo"©️😂Connected successfully to mySQL database!";


?>