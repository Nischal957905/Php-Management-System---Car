<?php
/*
This file is used for showing and projecting articles as well as editing and deleteing them from the database
also a link for the new creation is displayed here.
*/
/*
Refrence for various php codes used in the files.
w3Schools(n.d.)PHP Tutorial. w3Schools[online]. Available from: https://www.w3schools.com/php/default.asp[Accessed 10 September 2022]
*/

/*
Refrence for all php date and time codes used in the files.
w3Schools(n.d.)PHP Date and Time. w3Schools[online]. Available from: https://www.w3schools.com/php/php_date.asp[Accessed 10 September 2022]
*/

/*
Refrence for all the php sql and docker compose codes used in the files.
NILE - University of Northampton(n.d.)Module Activities. NILE - University of Northampton[online]. Available from: https://nile.northampton.ac.uk/ultra/courses/_126708_1/cl/outline[Accessed 10 September 2022]
*/

//this line of code helps in starting session
session_start();
//required the respective file at least ones in this file
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
//logic that will get executed when the delete button is clicked and a session is set 
//on delete articles file. This code just alerts that the article has been deleted.
if(isset($_SESSION['artDeleted'])){
	$echoError = "<script>alert('Article Deleted!')</script>";
    echo $echoError;
	unset($_SESSION['artDeleted']);
}
?>

<?php
require_once 'dbConnection.php';
?>
<!--
Structure designs that would be added in the front end page that this file refers to.
-->
<!DOCTYPE html>
<html>
	<head>
	<link rel="stylesheet" href="styles.css?v=<?php echo time();?>"/>
		<title>Northampton News - Article-Admin</title>
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
				<h2>Article utility area.</h2>
				<a href="addArticle.php" class="articleCreateMapper" style="text-decoration:none;display:inline-block;background-color:green;color:white;padding:10px;">New Article</a><br>
				<?php
					//Sql queries and implementation of php design for the article shown in the admin articles page.
					$queryArtProject = "SELECT article_id,title,publishDate,author,SUBSTR(content,1,30)
					FROM article
					ORDER BY publishDate DESC";
					$getProjectArt = $connection->prepare($queryArtProject);
					$getProjectArt->execute();
					while($eachArt = $getProjectArt->fetch(PDO::FETCH_ASSOC)){
						echo '<div class="outerCon">
							<div class="headStart">
								<h2>'.$eachArt['title'].'</h2>
							</div>
							<div class="dateStart">
								<div class="createStart">
									<p>'.$eachArt['publishDate'].'</p>
								</div>
							</div>
							<div class="contentStart">
								<p>'.$eachArt['SUBSTR(content,1,30)'].'</p>
							</div>
							<div class="authorStart">
								<p>'.$eachArt['author'].'</p>
							</div>
							<a class="moo" href="article.php?artId='.$eachArt['article_id'].'">view more</a>
							<a class="mo" href="editArticle.php?artId='.$eachArt['article_id'].'">edit</a>
							<a class="noo" onClick="return delArt()" href="deleteArticle.php?artId='.$eachArt['article_id'].'">delete</a>
							</div>
						';
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
			function delArt(){
				return confirm('Do you really want to delete this article?');
			}
		</script>
		<script>
			function logOut(){
				return confirm('Do you really want to logout?');
			}
		</script>
	</body>
</html>
