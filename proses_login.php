<?php
session_start();
include "inc/koneksi.php";

$user = $_POST['username'];
$pass = md5($_POST['password']);

$cek = mysqli_query($conn,"
SELECT * FROM admin
WHERE username='$user'
AND password='$pass'
");

if(mysqli_num_rows($cek)>0){

$_SESSION['login']=true;
$_SESSION['username']=$user;

header("Location:index.php");

}else{

header("Location:login.php");

}
?>