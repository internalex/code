<?php
include_once 'header.php';
include_once 'preparepdo.php';
include_once 'selectdata.php';
include_once 'users.php';
include_once 'generatepost.php';

if (!isset($_SESSION['username']) || $element['owner'] != usernameToInt($conn, $_SESSION['username'])) {
	if (isset($_GET['ref'])){
		header("Location: /" . $_GET['ref']);
	}
	else {
		header("Location: /index.php");
	}
	die();
}

// If the user decides to edit a post, this is where that happens

// When the edit post button is clicked
if (isset($_GET['complete']) && isset($_GET['val']) && $postExists && $_GET['complete'] == true && isset($_SESSION['username'])){
	// Verify that the user is the owner of this post
	if (intToUsername($conn, $element["owner"]) == $_SESSION['username'] && $element['owner'] == usernameToInt($conn, $_SESSION['username'])){
		$stmt = $conn->prepare("UPDATE blogposts SET title = ?, content = ?, tags = ? WHERE id = ?");
		$stmt->execute([$_POST['title'], $_POST['content'], $_POST['tags'], $element['id']]);
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
		<script src='main.js'></script>
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
						<p class="general">Edit mode</p>
						<hr>';
						echo edit_blogpost($conn, intToUsername($conn, $element['owner']), $element['timestamp'], $element['title'], $element['id'], $element['content'], $element['tags']);
						echo '</div><hr>
							<form action="edit.php?complete=true&val=' . $_GET['val'] . $ref . '" method="POST" onsubmit="return copycontent();">
							<div class="hidden">
								<input type="text" name="username" value="' . $_SESSION['username'] . '">						
								<input type="text" name="title" id="title-input">
								<input type="text" name="content" id="content-input">
								<input type="text" name="tags" id="tags-input">
							</div>
							<p class="general"><button>Edit Post</button></p>
			 			</form>';
					}
					else if ($_GET["confirm"] != "true") {
						echo '<p class="general">Post does not exist</p>';
					}
					else {
						echo '<p class="general">You do not have permission to edit this post!<br>Try logging out and back in again</p>';
					}
				?>
			</div>
		</div>
		
	</body>
</html>
