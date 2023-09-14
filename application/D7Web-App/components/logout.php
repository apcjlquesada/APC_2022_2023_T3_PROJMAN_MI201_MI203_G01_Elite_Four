<?php

include '../components/comconnect.php';

session_start();
session_unset();
session_destroy();

$_SESSION['msg'] = " <div class='alert-style'>  <div class='alert alert-danger'> Logged out. </div> </div>";
header('location:../customer/home.php');
 


?>