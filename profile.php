<?php
include_once 'preparepdo.php';
include_once 'users.php';
include_once 'header.php';
include_once 'generatepost.php';
?>
<!doctype html>
<html>
	<head>
		<?php echo $styles; ?>
	</head>
	<body>
		<div class="container">
			<?php echo getHeader(); ?>
			<div>
				<br>
				<?php 
					if($_GET["username"] == "no one" || !checkUserExists($conn, $_GET["username"])){
						echo "<p class='general'>User does not exist</p>";
					}
					else {
						$you = $_GET['username'] == $_SESSION["username"];
						echo "<p class='general'>Posts by " . $_GET["username"];
						if ($you) {
							echo " <a href='usersettings.php?ref=" . urlencode(basename($_SERVER['REQUEST_URI'])) . "'>(settings)</a>";
						}
						echo "</p><hr>";
						$posts = getUserPosts($conn, $_GET["username"]);
						$ctr = 0;
						foreach ($posts as $element) {
							if ($ctr != 0) {
								echo "<hr>";
							}
							if ($you){
								echo nouserlink_deletable_blogpost($conn, $_SESSION['username'], $element['timestamp'], $element['title'], $element['id'], $element['content'], $element['tags']);
							}
							else{
								echo nouserlink_blogpost($conn, intToUsername($conn, $element['owner']), $element['timestamp'], $element['title'], $element['id'], $element['content'], $element['tags']);
							}
							$ctr++;
						}	
						if($ctr == 0){
							echo "<p class='general'>This user has no posts</p>";			
						}
					}
				?>			
			</div>
		</div>
	</body>
</html>
