<?php
//this line of code helps in starting session
session_start();
//required the respective file at least ones in this file
require_once 'dbConnection.php';
require_once 'insertInto.php';
//code to be executed when the register button is clicked
if(isset($_POST['submit'])){
    //siml=ple logic to check if fields are empty
    if(!empty($_POST['registerName']) && !empty($_POST['registerSur']) &&
    !empty($_POST['registerAge']) && !empty($_POST['registerEmail']) &&
    !empty($_POST['registerEmail']) && !empty($_POST['registerUser']) &&
    !empty($_POST['registerPass']) && isset($_POST['registerGender'])){

        makeNewUser($_POST['registerName'],$_POST['registerSur'],$_POST['registerAge'],
        $_POST['registerGender'],$_POST['registerEmail'],$_POST['registerUser'],
        $_POST['registerPass']);
        $_SESSION['newAcc'] = true;
        header('location: login.php');
        die();
    }
    else{
        $echoError = "<script>alert('Input fields are empty!')</script>";
		echo $echoError;
    }
}

?>
<!--
Structure designs that would be added in the front end page that this file refers to.
-->
<!DOCTYPE html>
<html>
	<head>
        <link rel="stylesheet" href="login.css?v=<?php echo time();?>"/>
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
		<title>Northampton News - Register</title>
	</head>
	<body>
        <div class="he">
            <div id="registerPart">
            <h1>Sign up your Account</h1>
            <!-- form to be submitted for registering -->
                <form action="register.php" method="POST" class="registerLa">
                    <div class="inner">
                        <div class="ouIn">
                        <label>First name<label><br><input type="text" name="registerName"/>
                        </div>
                        <div class="ouIn">
                        <label>Last name<label><br><input type="text" name="registerSur"/>
                        </div>
                        <div class="ouIn">
                        <label>Age<label><br><input type="number" name="registerAge"/>
                        </div>
                        <div class="ouIn">
                        <label>Email<label><br><input type="email" name="registerEmail"/>
                        </div>
                        <div class="ouIn">
                        <label>User name<label><br><input type="text" name="registerUser"/>
                        </div>
                        <div class="ouIn">
                        <label>password<label><br><input type="text" name="registerPass"/>
                        </div>
                        <div class="ouIn">
                        <label>Male</label><input type="radio" name="registerGender" value="male"/>
                        <label>Female</label><input type="radio" name="registerGender" value="female"/><br>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="btnRe">
                    <button type="submit" name="submit" value="submit">Register</button>
                    </div>
                </form>
            </div>
        </div>
	</body>
</html>