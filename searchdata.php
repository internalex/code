<?php
include_once 'users.php';
include_once 'header.php';

$stmt = $conn->prepare("SELECT *,
	MATCH (title, content, tags) against (:keyword IN NATURAL LANGUAGE MODE) AS rel
FROM blogposts 
WHERE 
	displayed = 1 
and
	MATCH (title, content, tags) against (:keyword IN NATURAL LANGUAGE MODE)
and
	(visible = 0 or visible = :uid)
ORDER BY 
	rel DESC, likes DESC");
$stmt->bindValue(":keyword", $_GET['search']);
$stmt->bindValue(":uid", usernameToInt($conn, $_SESSION['username']));
$stmt->execute();

