<?php
include_once 'preparepdo.php';
include_once 'selectdata.php';
include_once 'header.php';
include_once 'generatepost.php';
include_once 'users.php';
include_once 'posts.php';

?>
<!doctype html>
<html>
	<head>
		<?php echo $styles; ?>
	</head>
	<body class="container">
		<?php echo getHeader(); ?>
		<br>
		<div class="col-sm">
			<?php
			$username = intToUsername($conn, $element['owner']);
			if (!$postExists) {
				echo "<p class='general'>Post does not exist or is not public</p>";
			}
			else if(isset($_SESSION['username']) && $_SESSION['username'] == $username) {
				echo partial_deletable_blogpost($conn, $username, $element['timestamp'], $element['title'], $element['id'],  $element['content'], $element['tags']);
			}
			else {
				echo partial_blogpost($conn, $username, $element['timestamp'], $element['title'], $element['id'], $element['content'], $element['tags']);
			}
			?>
		</div>
	</body>
</html>

