<?php
if(isset($_POST['Register'])){
header('location: register.html');
}
else{
session_start();
$con = mysqli_connect("localhost","ams_user14","Cmpt354@123","ams_g14");
if(mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$tbl_name="Customer"; // Table name 
$username = $_POST['myusername'];
$password = $_POST['mypassword'];

$sql = "SELECT * FROM $tbl_name WHERE cid = '$username' AND password = '$password'";
$result = mysqli_query($con, $sql);
$count = mysqli_num_rows($result);

if($count ==1){
$_SESSION['username'] = $username;
$_SESSION['password'] = $password;
header('location: index.html');
}
else{
header('location: loginfailed.html');
}
}
?>