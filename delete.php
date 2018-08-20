<?php
include_once 'header.php';
include_once 'preparepdo.php';
include_once 'selectdata.php';
include_once 'users.php';
include_once 'generatepost.php';

if (!isset($_SESSION['username']) || $element['owner'] != usernameToInt($conn, $_SESSION['username'])){
	if (isset($_GET['ref'])){
		header("Location: /" . $_GET['ref']);
	}
	else {
		header("Location: /index.php");
	}
	die();
}

// If the user decides to delete the post, this is where that happens

// When the delete post button is clicked
if (isset($_GET['confirm']) && isset($_GET['val']) && $postExists && $_GET['confirm'] == true && isset($_SESSION['username'])){
	// Verify that the user is the owner of this post
	if (intToUsername($conn, $element["owner"]) == $_SESSION['username'] && usernametoInt($conn, $_SESSION['username']) == $element['owner']){
		$stmt = $conn->prepare("UPDATE blogposts SET displayed = 0 WHERE id = ?");
		$stmt->execute([$element['id']]);
		if (isset($_GET['ref'])){
			header("Location: /" . $_GET['ref']);
		}
		else {
			header("Location: /index.php");
		}
		die();
	}
}
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
				<?php
					echo '<br>';
					$ref = '';
					if (isset($_GET['ref'])) {
						$ref = '&ref='.urlencode($_GET['ref']);
					}
					if ($postExists && !(isset($_GET['confirm']) && $_GET['confirm'] == "true")){
						echo '
						<p class="general">Are you sure you want to delete this post?</p>
						<hr><div class="red">';
						echo nouserlink_blogpost($conn, intToUsername($conn, $element['owner']), $element['timestamp'], $element['title'], $element['id'], $element['content'], $element['tags']);
						echo '</div><hr>
							<form action="delete.php?confirm=true&val=' . $_GET['val'] . $ref . '" method="POST">
							<div class="hidden">
								<input type="text" name="username" value="' . $_SESSION['username'] . '">						
							</div>
							<p class="general"><button>Delete post</button></p>
			 			</form>';
					}
					else if ($_GET["confirm"] != "true") {
						echo '<p class="general">Post does not exist</p>';
					}
					else {
						echo '<p class="general">You do not have permission to delete this post!<br>Try logging out and back in again</p>';
					}
				?>
			</div>
		</div>
		
	</body>
</html>
