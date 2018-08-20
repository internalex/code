<?php
include_once 'users.php';
include_once 'header.php';

$postExists = false;
if (isset($_GET['val'])){
	$stmt = $conn->prepare('SELECT count(*) FROM blogposts WHERE id = ? and displayed = 1 and (visible = 0 or visible = ?)');
	$stmt->execute([intval($_GET['val']), usernameToInt($conn, $_SESSION['username'])]);
	if ($stmt->fetchColumn() > 0){
		$postExists = true;
	}
}

$stmt = null;
$element = null;

if ($postExists) {
	$stmt = $conn->prepare('SELECT * FROM blogposts WHERE id = ? and displayed = 1 and (visible = 0 or visible = ?)');
	$stmt->execute([intval($_GET['val']), usernameToInt($conn, $_SESSION['username'])]);
	$element = $stmt->fetch(PDO::FETCH_ASSOC);
}

