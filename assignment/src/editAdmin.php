<?php
/*
This page is responsible for holding the codes that gets executed when the edit admin button is clicked and
edits them
*/
//this line of code helps in starting session
session_start();
//required the respective file at least ones in this file
require_once 'dbConnection.php';
require_once 'insertInto.php';
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
//Logic to edit the changes that has been made into the database.
if(isset($_POST['submit'])){
	$intVal = (int)$_POST['adminId'];
	$_SESSION['onBoardUser'] = $_POST['adminEmail'];
	updateAdmin($intVal,$_POST['adminUser'],$_POST['adminEmail']);
	header('location: editAdmin.php?adminId='.$intVal);
}
?>
<!--
Structure designs that would be added in the front end page that this file refers to.
-->
<!DOCTYPE html>
<html>
	<head>
        <link rel="stylesheet" href="styles.css?v=<?php echo time();?>"/>
		<title>Northampton News - Edit-Admin</title>
	</head>
	<body>
		<header>
			<section>
				<h1 style="font-size=20px;">Northampton News</h1>
			</section>
		</header>
		<nav>
			<ul>
				<li><a href="index.php">Latest Articles</a></li>
				<li><a href="#">Select Category</a>
					<ul>
					<?php
					//Logic that handles the projection of all categories in the drop down menu.
					$projectCategories = "SELECT category_id,name,description FROM category";
					$storeProjection = $connection->prepare($projectCategories);
					$storeProjection->execute();
					while($eachCat = $storeProjection->fetch(PDO::FETCH_ASSOC)){
						echo '<li><a href="categoryPage.php?catId='.$eachCat['category_id'].'">'.$eachCat['name'].'</a></li>';
					}
				?>
					</ul>
				</li>
				<?php
					//Logic that will check if user is logged in or not. Then if user is logged in shows logout 
					//in navigation bar else shows login.
					if(isset($_SESSION['onBoard'])){
						if($_SESSION['onBoard'] == false){
							echo '<li><a href="login.php">Login</a></li>';
						}
						else{
							echo '<li><a onClick="return logOut()" href="logout.php?loginStatus=true">Logout</a></li>';
						}
					}
					else{
						echo '<li><a href="login.php">Login</a></li>';
					}
				?>
			</ul>
		</nav>
		<main style="width:98.7vw;">
			<!-- Delete the <nav> element if the sidebar is not required -->
			<nav>
				<ul>
					<li><a href="adminArticles.php">Articles</a></li>
					<li><a href="adminCategories.php">Categories</a></li>
					<li><a href="users.php">Users</a></li>
					<li><a href="manageAdmins.php">Manage Admin</a></li>
				</ul>
			</nav>

			<article>
                <?php
				//SQL queries and php codes to print the current unedited admin user data into input fields so
				//that they can be edited with the help of forms below
					$projectAdmin = "SELECT user_id,email,username FROM users
                    WHERE user_id=:user_id";
					$storeProjection = $connection->prepare($projectAdmin);
					$storeProjection->execute(['user_id'=>$_GET['adminId']]);
					while($eachAdmin = $storeProjection->fetch(PDO::FETCH_ASSOC)){
						echo '<h2>'.$eachAdmin['username'].'</h2><br>
                        <p>Currently editing '.$eachAdmin['username'].'</p>
						<form action="editAdmin.php" method="POST">
							<input type="hidden" name="adminId" value='.$eachAdmin['user_id'].'/>
                            <label>Email:</label><input type="text" name="adminEmail" value="'.$eachAdmin['email'].'"/>
                            <label>Username:</label><input type="text" name="adminUser" value="'.$eachAdmin['username'].'"/>
							<input type="submit" name="submit" value="save changes" />
						</form></a>';
					}
				?>
			</article>
		</main>

		<footer>
			&copy; Northampton News 2017
		</footer>
		<!--  Javascript functions that will be used for alerting and 
		confirming while logging out, and other activities -->
		<script>
			function logOut(){
				return confirm('Do you really want to logout?');
			}
		</script>
	</body>
</html>