<?php
/*
Page to hold the comments made by a user in a all articles
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
?>
<!--
Structure designs that would be added in the front end page that this file refers to.
-->
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="styles.css?v=<?php echo time();?>"/>
		<title>Northampton News - Admin-Comments</title>
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
				<h2>All comments</h2>
				<p style="padding-bottom:10px; border-bottom:2px solid black; margin-bottom:30px;">Comments made by user are listed here!</p>
                <?php
				//SQL and php codes for showing the users comments provided all over the history.
                    $userIntId = (int)$_GET['userId'];
                    $articleUser = "SELECT DISTINCT article_id FROM comment WHERE user_id=:user_id";
                    $getUserArt = $connection->prepare($articleUser);
                    $getUserArt->execute(['user_id'=>$userIntId]);
                    while($eachUser = $getUserArt->fetch(PDO::FETCH_ASSOC)){
                        $inUserArt = (int)$eachUser['article_id'];
                        $artName = "SELECT title,article_id FROM article WHERE article_id=:article_id";
                        $getArtName = $connection->prepare($artName);
                        $getArtName->execute(['article_id'=>$inUserArt]);
                        while($eachArt = $getArtName->fetch(PDO::FETCH_ASSOC)){
                            $newArt = (int)$eachArt['article_id'];
                            echo '<h3>'.$eachArt['title'].'</h3><ul>';
                            $commentOnUser = "SELECT comment_desc,date_of_creation FROM comment WHERE user_id=:user_id AND article_id=:article_id";
                            $getArticlesComment = $connection->prepare($commentOnUser);
                            $getArticlesComment->bindParam(':user_id',$user_id);
                            $user_id = $userIntId;
                            $getArticlesComment->bindParam(':article_id',$article_id);
                            $article_id = $newArt;
                            $getArticlesComment->execute();
                            while($eachUnArt = $getArticlesComment->fetch(PDO::FETCH_ASSOC)){
                                echo '
								<br><li>'.$eachUnArt['comment_desc'].'<br><br><p>Made on: '.$eachUnArt['date_of_creation'].'</p></li>';
                            }
                            echo '</ul>';
                        }
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
