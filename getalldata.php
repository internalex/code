<?php
include_once 'header.php';
include_once 'users.php';

$stmt = null;

try {
	$stmt = $conn->prepare("SELECT * FROM blogposts WHERE displayed = 1 and (visible = 0 or visible = ?) ORDER BY likes DESC, id DESC;");
	$stmt->execute([usernameToInt($conn, $_SESSION['username'])]);
}
catch(PDOException $e) {
	echo $e->getMessage();
}


