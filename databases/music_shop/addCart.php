<?php
session_start();

$upc = $_POST['upc'];
$cid = $_SESSION['username'];
$qty = $_POST['amount'];

if($cid == ''){
header('location: login.html');
} else {

$con = mysqli_connect("localhost","ams_user14","Cmpt354@123","ams_g14");
if(mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Insert Item To Basket for this CustomerID
$tableName="Basket";
$sql = "INSERT INTO Basket(cid, upc, qty) VALUES ('$cid', $upc, $qty)";
echo $sql;
mysqli_query($con, $sql);
header('location: viewCart.php');

}
?>