<?php
include_once 'header.php';
include_once 'preparepdo.php';
include_once 'users.php';

$reason = "none";
if(array_key_exists("reason", $_GET)){
	$reason = $_GET["reason"];
}
$message = "";

if($reason == "signup") {
	$message = createUser($conn, $_POST["username"], $_POST["password"], $_POST["confirm"]);	
	if ($message == "success") {
		$_SESSION["username"] = $_POST["username"];
		if (isset($_GET['ref'])){
			header("Location: /".$_GET['ref']);
		}
		else {
			header("Location: /index.php");
		}
		die();
	}
}

if($reason == "login") {
	$message = loginUserStatus($conn, $_POST["username"], $_POST["password"]);
	if ($message == "success") {
		$_SESSION["username"] = $_POST["username"];
		if (isset($_GET['ref'])){
			header("Location: /".$_GET['ref']);
		}
		else {
			header("Location: /index.php");
		}
		die();
	}

}

// Log out the user. If there is a reference, put them back there
if($reason == "logout") {
	$_SESSION["username"] = NULL;
	$_SESSION["password"] = NULL;
	if (isset($_GET['ref'])){
		header("Location: /".$_GET['ref']);
		die();
	}
}

// Preserve the reference tag if there is one
$ref = "";
if (isset($_GET['ref'])){
	$ref = "&ref=".$_GET['ref'];
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
	</div>
	<div id="loginwindow" class="container">
		<div class="logindiv row">
			<div class="l col-6">
				<em>Sign up</em>
				<form class="loginform" action="login.php?reason=signup<?php echo $ref; ?>" method="POST">
					<?php
						if ($reason == "signup") {
							echo($message);	
						}						
					?>
					<span>Username: <input name="username" type="text" placeholder="Username"></span>
					<span>Password: <input name="password" type="password" placeholder="Password"></span>
					<span>Confirm Password: <input name="confirm" type="password" placeholder="Password"></span>
					<button class="btn btn-primary">Create Account</button>
				</form>
			</div>
			<div class="r col-6">
				<em>Login</em>
				<form class="loginform" action="login.php?reason=login<?php echo $ref; ?>" method="POST">
					<?php
						if ($reason == "login") {
							echo($message);	
						}						
					?>
					<span>Username: <input name="username" type="text" placeholder="Username" name="username"></span>
					<span>Password: <input name="password" type="password" placeholder="Password" name="password"></span>
					<button class="btn btn-primary">Log in</button>
				</form>
			</div>	
		</div>
	</div>
	</body>
</html>
