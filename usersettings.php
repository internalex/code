<?php
include 'header.php';
include 'preparepdo.php';
include 'users.php';

if (!isset($_SESSION['username'])){
	if (isset($_GET['ref'])){
		header("Location: /".$_GET['ref']);
	}
	else {
		header("Location: /index.php");
	}
	die();
}

$ref = "";
if (isset($_GET['ref'])) {
	$ref = "&ref=".urlencode($_GET['ref']);
}

$message = "";
// Handle a user changing their password
if (isset($_GET['reason']) && $_GET['reason'] == "changepassword"){
	if (loginUserBool($conn, $_SESSION['username'], $_POST['prev'])){
		$message = updateUserPassword($conn, $_SESSION['username'], $_POST['new'], $_POST['confirm']);
		if ($message == "success") {
			$message = "<span class='green'>Password updated successfully!</span><br><br>";
		}	
	}
	else {
		$message = "<span class='red'>Previous password does not match one on file...</span><br><br>";
	}
}

// Handle a user deleting all their posts
if (isset($_GET['reason']) && $_GET['reason'] == "deleteallposts") {
	$conf = $_POST['confirmdelete'];
	if ($conf == "yes" || $conf == "Yes" || $conf == "YES"){
		$stmt = $conn->prepare("UPDATE blogposts SET displayed = 0 WHERE owner = ?");
		$stmt->execute([usernameToInt($conn, $_SESSION['username'])]);
		$message = "<span class='green'>All posts deleted!</span><br><br>";
	}
	else {
		$message = "<span class='red'>You didn't type 'Yes' in the input box...</span><br><br>";
	}
}

if (isset($_GET['reason']) && $_GET['reason'] == "hideallposts") {
	$stmt = $conn->prepare("UPDATE blogposts SET visible = ? WHERE owner = ?");
	$stmt->execute([usernameToInt($conn, $_SESSION['username']), usernameToInt($conn, $_SESSION['username'])]);
	$message = "<span class='green'>All posts hidden</span><br><br>";
}

if (isset($_GET['reason']) && $_GET['reason'] == "showallposts") {
	$stmt = $conn->prepare("UPDATE blogposts SET visible = 0 WHERE owner = ?");
	$stmt->execute([usernameToInt($conn, $_SESSION['username'])]);
	$message = "<span class='green'>All posts shown</span><br><br>";
}

?>
<!doctype html>
<html>
	<head>
	<?php echo $styles; ?>
	</head>
	<body>
		<div class='container'>
			<?php echo getHeader(); ?>
			<br>
			<div>
				<div>
					<p class="title">Change your password:</p>
					<hr>
					<div class='blogpost'>
						<?php 
							if (isset($_GET['reason']) && $_GET['reason'] == "changepassword"){
								echo $message;	
							}
						?>
						<form class='stdinput' action="?reason=changepassword<?php echo $ref; ?>" method="POST">
							Previous password: <input type='password' placeholder='Previous Password' name='prev'>
							<br><br>
							New Password: <input type='password' placeholder='New Password' name='new'>
							<br><br>
							Confirm New Password: <input type='password' placeholder='Confirm New Password' name='confirm'>
							<br><br>
							<button class="btn btn-primary">Change Password</button>
						</form>
					</div>
					<br>
					<p class="title red">Delete all posts:</p>
					<hr>
					<div class='blogpost'>
						<?php 
							if (isset($_GET['reason']) && $_GET['reason'] == "deleteallposts"){
								echo $message;	
							}
						?>
						<form class='stdinput' action="?reason=deleteallposts<?php echo $ref; ?>" method='POST'>
							Are you sure? <input placeholder="Yes / No" name='confirmdelete'>
							<br><br>
							<button class='btn btn-danger'>Delete all posts</button>
						</form>	
					</div>
					<p class='title'>Hide all posts:</p>
					<hr>	
					<div class='blogpost'>
						<?php 
							if (isset($_GET['reason']) && ($_GET['reason'] == "hideallposts" || $_GET['reason'] == "showallposts")){
								echo $message;	
							}
						?>
						<form class='stdinput' action="?reason=hideallposts<?php echo $ref; ?>" method='POST'>
							<button class="btn btn-secondary">Hide all posts</button>
						</form>	
						<br>
						<form class='stdinput' action="?reason=showallposts<?php echo $ref; ?>" method='POST'>
							<button class="btn btn-secondary">Show all posts</button>
						</form>	
					</div>
						
				</div>
			</div>		
		</div>
	</body>
</html>
