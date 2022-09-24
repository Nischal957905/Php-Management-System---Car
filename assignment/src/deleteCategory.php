<?php
/*
This page is responsible for holding the codes that gets executed when the delete category button is clicked and
deletes them
*/
//this line of code helps in starting session
session_start();
//required the respective file at least ones in this file
require_once 'insertInto.php';
require_once 'dbConnection.php';
//logic on if statement that controls the authorization in this page.
//Allows only users with permissions to access this pages protected content.
if(!isset($_SESSION['onBoard']) && !isset($_SESSION['onBoardUser'])){
	header('location: noAccess.php');
	die();
}
//logic to identify if the logged in user is admin or not.
//Only allows admin users to access the protected content in this page
if(isset($_SESSION['onBoardUser'])){
	$valStatus = verifyAdmin($_SESSION['onBoardUser']);
	if($valStatus == false){
		header('location: noAccess.php');
	    die();
	}
}
//Logic that lets us delete the categories when the delete button is clicked
if(isset($_GET['delCat'])){
	$intValCat = (int)$_GET['delCat'];
	$val = delCat($intValCat);
	if($val == true){
		$_SESSION['catDeleted'] = true;
		header('location: adminCategories.php');
	}
	else{
		$_SESSION['catDeleted'] = false;
		header('location: adminCategories.php');
	}
}
?>