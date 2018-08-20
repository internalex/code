<?php

function getNumberLikes($conn, $id){
	$stmt = $conn->prepare("SELECT likes FROM blogposts WHERE id = ?");
	$stmt->execute([$id]);
	return $stmt->fetchColumn();	
}

function nextLikeState($conn, $uid, $pid){
	$stmt = $conn->prepare("SELECT count(*) FROM likes WHERE userid = ? and postid = ?");
	$stmt->execute([$uid, $pid]);
	$n = $stmt->fetchColumn();
	if ($n == 0) {
		return "true";
	}
	return "false";
}

function likePost($conn, $username, $pid){

	$uid = usernameToInt($conn, $username);

	$nls = nextLikeState($conn, $uid, $pid);
	
	if ($nls == "true") {
		$stmt = $conn->prepare("INSERT INTO likes (userid, postid) VALUES (?, ?)");
		$stmt->execute([$uid, $pid]);
		$stmt = $conn->prepare("SELECT count(*) FROM likes WHERE postid = ?");
		$stmt->execute([$pid]);
		$numLikes = $stmt->fetchColumn();
		$stmt = $conn->prepare("UPDATE blogposts SET likes = ? WHERE id = ?");
		$stmt->execute([$numLikes, $pid]);
	} else {
		$stmt = $conn->prepare("DELETE FROM likes WHERE userid = ? and postid = ?");
		$stmt->execute([$uid, $pid]);
		$stmt = $conn->prepare("SELECT count(*) FROM likes WHERE postid = ?");
		$stmt->execute([$pid]);
		$numLikes = $stmt->fetchColumn();
		$stmt = $conn->prepare("UPDATE blogposts SET likes = ? WHERE id = ?");
		$stmt->execute([$numLikes, $pid]);

	}
}

function currentVisibilityState($conn, $pid){
	$stmt = $conn->prepare("SELECT visible FROM blogposts WHERE id = ?");
	$stmt->execute([$pid]);
	return $stmt->fetchColumn();
}


// Visibility allows one user to be able to see a post that they created. Zero is no visibility
function toggleHiddenPost($conn, $username, $pid) {

	$uid = usernameToInt($conn, $username);

	if (currentVisibilityState($conn, $pid) != $uid) {
		$stmt = $conn->prepare("UPDATE blogposts SET visible = ? WHERE id = ?");
		$stmt->execute([$uid, $pid]);
	} else {
		$stmt = $conn->prepare("UPDATE blogposts SET visible = ? WHERE id = ?");
		$stmt->execute([0, $pid]);
	}
}
