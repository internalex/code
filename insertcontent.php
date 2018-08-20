<?php
include 'preparepdo.php';
include 'users.php';

$success = false;
$id = 0;

if ($_POST["title"] != "Your blogpost's title") {
	$success = true;
	$stmt = $conn->prepare('INSERT INTO blogposts (title, content, tags, owner) VALUES (?, ?, ?, ?); SELECT LAST_INSERT_ID()');
	$stmt->execute([strip_tags($_POST["title"]), strip_tags($_POST["content"]), strip_tags($_POST["tags"]), usernameToInt($conn, $_POST['username'])]);
	$id = $conn->lastInsertId();
}
