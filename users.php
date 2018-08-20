<?php

// File for craeting, deleting, and managing users signing in and out

// Check if a user alraedy exists
function checkUserExists($conn, $username) {
	$stmt = $conn->prepare('SELECT count(*) FROM users WHERE username = ?');
	$stmt->execute([$username]);
	$n = $stmt->fetchColumn();

	if ($n == 0) {
		return false;	
	}
 	else {
		return true;
	}
}

// Create a user after checking if one exists under the same name
function createUser($conn, $username, $password, $confirm) {
	if ($username == "") {
		return "<span class='red'>Username can not be empty</span>";
	}
	if (checkUserExists($conn, $username)) {
		return "<span class='red'>User already exists!</span>";
	}
	if ($password != $confirm) {
		return "<span class='red'>Passwords do not match</span>";
	}
	$stmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
	$stmt->execute([strip_tags($username), password_hash($password, PASSWORD_DEFAULT)]);	
	return "success";	
}

// Updates an existsing user's password
function updateUserPassword($conn, $username, $password, $confirm) {
	if ($password != $confirm) {
		return "<span class='red'>Passwords do not match</span>";
	}
	$stmt = $conn->prepare('UPDATE users SET password = ? WHERE username = ?');
	$stmt->execute([password_hash($password, PASSWORD_DEFAULT), strip_tags($username)]);	
	return "success";	
}

// Returns true/false if the user login is correct, but doesn't check if login exists
function loginUserBase($conn, $username, $password) {
	$stmt = $conn->prepare('SELECT password FROM users WHERE username = ?');
	$stmt->execute([$username]);
	$hashed = $stmt->fetch(PDO::FETCH_ASSOC)['password'];
	
	if (password_verify($password, $hashed)) {
		return true;
	}

	return false;

}

// Returns a status, as used in login.php
function loginUserStatus($conn, $username, $password) {
	if (!checkUserExists($conn, $username)) {
		return "<span class='red'>User does not exist</span>";
	}
	if (loginUserBase($conn, $username, $password)) {
		return "success";
	}

	return "<span class='red'>incorrect username or password</span>";
	
}

// Returns a bool of whether the login was correct
function loginUserBool($conn, $username, $password) {
	if (!checkUserExists($conn, $username)) {
		return false;
	}
	if(loginUserBase($conn, $username, $password)) {
		return true;
	}
	return false;
}

// Converts any username to the coresponding indentifier int
function usernameToInt($conn, $username) {
	$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
	$stmt->execute([$username]);
	$id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
	return $id;
}

// Converts any identifier int to a username 
function intToUsername($conn, $id) {
	if ($id == 0) {
		$id = 1;
	}
	$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
	$stmt->execute([intval($id)]);
	$username = $stmt->fetch(PDO::FETCH_ASSOC)['username'];
	return $username;
}

// Get the posts of a specific user
function getUserPosts($conn, $username) {
	$id = usernameToInt($conn, $username);	
	$stmt = $conn->prepare("SELECT * FROM blogposts WHERE owner = ? and displayed = 1 and (visible = 0 or visible = ?)");
	$stmt->execute([$id, usernameToInt($conn, $_SESSION['username'])]);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
