<?php
include_once 'posts.php';
include_once 'users.php';
include_once 'preparepdo.php';
include_once 'dealwithurls.php';

if (isset($_GET['like'])){
	likePost($conn, $_SESSION['username'], $_GET['like']);
	header("Location: " . removeQueryParam(basename($_SERVER['REQUEST_URI']), "like"));
}

function likeDisplay($conn, $id){
	$n = getNumberLikes($conn, $id);
	$n = "<p class='like'>" . $n. " like";
	if (getNumberLikes($conn, $id) != 1) {
		$n = $n . "s";	
	}
	if(isset($_SESSION['username'])){
		$mod = "?like=" . $id;
		if (count($_GET) != 0) {
			$mod = "&like=" . $id;
		}
		if (isset($_GET['like']) && $_GET['like'] == $id){
			$mod = "";
		}
		$uri = basename($_SERVER['REQUEST_URI']);
		if ($uri == ""){
			$uri="index.php";
		}
		$nls = nextLikeState($conn, usernameToInt($conn, $_SESSION['username']), $id);
		$like = "<form action='" . $uri . $mod . "#bp" . $id . "' method='POST'>" . $n;
		if ($nls == "false") {
			return $like . " <button>👎</button></p></form>";
		}
		else {
			return $like . " <button>👏</button></p></form>";
		}
	}
	else {
		return $n . "</p>";
	}
}

if (isset($_GET['hide'])){
	toggleHiddenPost($conn, $_SESSION['username'], $_GET['hide']);
	header("Location: " . removeQueryParam(basename($_SERVER['REQUEST_URI']), "hide"));
}

function hideButton($conn, $id) {
	
	$uri = basename($_SERVER['REQUEST_URI']);
	if ($uri == ""){
		$uri="index.php";
	}
	$mod = "?hide=" . $id;
	if (count($_GET) != 0) {
		$mod = "&hide=" . $id;
	}

	$visState = currentVisibilityState($conn, $id);
	if ($visState == 0) {
		return "<a href='" . $uri . $mod . "#bp" . $id . "'>🙈</a>";
	} else {
		return "<a href='" . $uri . $mod . "#bp" . $id . "'>🐵</a>";		
	}
}

function hideClass($conn, $id) {
	$visState = currentVisibilityState($conn, $id);
	if ($visState != 0) {
		return array("grey", " - HIDDEN");
	}
	return array("", "");
}

// Immutable blogposts:

// Fucntion to genraete a formatted blogpost
function blogpost($conn, $owner, $timestamp, $title, $id, $content, $tags) {
	return "<div class='blogpost' id='bp" . $id . "'><p class='time'>Written by <a href='profile.php?username=" . $owner . "'>" . $owner . "</a> on " . $timestamp . "</p><p class='title'><a href='post.php?val=" . $id . "'>" . strip_tags($title) . "</a></p><p class='content'>" . strip_tags($content) . "</p><p class='tags'>" . strip_tags($tags) . "</p>" . likeDisplay($conn, $id) . "</div>\n";
} 

// Function to generate a blogpost without a link to it (post.php and submit.php)
function partial_blogpost($conn, $owner, $timestamp, $title, $id, $content, $tags){
	return "<div class='blogpost' id='bp" . $id . "'><p class='time'>Written by <a href='profile.php?username=" . $owner . "'>" . $owner . "</a> on " . $timestamp . "</p><p class='title'>" . strip_tags($title) . "</p><p class='content'>" . strip_tags($content) . "</p><p class='tags'>" . strip_tags($tags) . "</p>" . likeDisplay($conn, $id) . "</div>\n";
}

// Function to generate a blogpost without a link to the user's page. Used on profile.php
function nouserlink_blogpost($conn, $owner, $timestamp, $title, $id, $content, $tags) {
	return "<div class='blogpost' id='bp" . $id . "'><p class='time'>Written by " . $owner . " on " . $timestamp . "</p><p class='title'><a href='post.php?val=" . $id . "'>" . strip_tags($title) . "</a></p><p class='content'>" . strip_tags($content) . "</p><p class='tags'>" . strip_tags($tags) . "</p>" . likeDisplay($conn, $id) . "</div>\n";
}


// Deleteable blocposts: (which are also editiable and hida-able)

// When a user 'owns' a blogpost it should be deletable
function deletable_blogpost($conn, $owner, $timestamp, $title, $id, $content, $tags) {
	$trash = "<a href='delete.php?val=" . $id . "&ref=" . basename($_SERVER['REQUEST_URI']) . "'>🗑️</a>";
	$edit = "<a href='edit.php?val=" . $id . "&ref=" . basename($_SERVER['REQUEST_URI']) . "'>✏️</a>";
	$hide = hideButton($conn, $id);
	$hideClass = hideClass($conn, $id);
	return "<div class='blogpost " . $hideClass[0] . "' id='bp" . $id . "'><p class='time'>Written by <a href='profile.php?username=" . $owner . "'>" . $owner . "</a> on " . $timestamp . " " . $edit . $trash . $hide . "</p><p class='title'><a href='post.php?val=" . $id . "'>" . strip_tags($title) . $hideClass[1] ."</a></p><p class='content'>" . strip_tags($content) . "</p><p class='tags'>" . strip_tags($tags) . "</p>" . likeDisplay($conn, $id) . "</div>\n";
} 

// Shows a blogpost without a direct link to it but is deletable
function partial_deletable_blogpost($conn, $owner, $timestamp, $title, $id, $content, $tags) {
	$trash = "<a href='delete.php?val=" . $id . "'>🗑️ </a>";
	$edit = "<a href='edit.php?val=" . $id . "&ref=" . basename($_SERVER['REQUEST_URI']) . "'>✏️</a>";
	$hide = hideButton($conn, $id);
	$hideClass = hideClass($conn, $id);
	return "<div class='blogpost " . $hideClass[0] . "' id='bp" . $id . "'><p class='time'>Written by <a href='profile.php?username=" . $owner . "'>" . $owner . "</a> on " . $timestamp . " " . $edit . $trash . $hide . "</p><p class='title'>" . strip_tags($title) . $hideClass[1] . "</p><p class='content'>" . strip_tags($content) . "</p><p class='tags'>" . strip_tags($tags) . "</p>" . likeDisplay($conn, $id) . "</div>\n";
}

// Shows a blogpost without a direct link to it but is deletable. Also lacks a like button
function nolike_partial_deletable_blogpost($conn, $owner, $timestamp, $title, $id, $content, $tags) {
	$trash = "<a href='delete.php?val=" . $id . "'>🗑️ </a>";
	$edit = "<a href='edit.php?val=" . $id . "'>✏️</a>";
	return "<div class='blogpost' id='bp" . $id . "'><p class='time'>Written by <a href='profile.php?username=" . $owner . "'>" . $owner . "</a> on " . $timestamp . " " . $edit . $trash . "</p><p class='title'>" . strip_tags($title) . "</p><p class='content'>" . strip_tags($content) . "</p><p class='tags'>" . strip_tags($tags) . "</p></div>\n";
}

// When on a user's page there shouldn't be the blue link to the same page but they should still be deletable if owned
function nouserlink_deletable_blogpost($conn, $owner, $timestamp, $title, $id, $content, $tags) {
	$trash = "<a href='delete.php?val=" . $id . "&ref=" . basename($_SERVER['REQUEST_URI']) . "'>🗑️</a>";
	$edit = "<a href='edit.php?val=" . $id . "&ref=" . basename($_SERVER['REQUEST_URI']) . "'>✏️</a>";
	$hide = hideButton($conn, $id);
	$hideClass = hideClass($conn, $id);
	return "<div class='blogpost " . $hideClass[0] . "' id='bp" . $id . "'><p class='time'>Written by " . $owner . " on " . $timestamp . " " . $edit . $trash . $hide . "</p><p class='title'><a href='post.php?val=" . $id . "'>" . strip_tags($title) . $hideClass[1] . "</a></p><p class='content'>" . strip_tags($content) . "</p><p class='tags'>" . strip_tags($tags) . "</p>" . likeDisplay($conn, $id) . "</div>\n";
} 


// Editable blogpost
function edit_blogpost($conn, $owner, $timestamp, $title, $id, $content, $tags) {
	return "<div class='blogpost'><p class='time'>Written by " . $owner . " on " . $timestamp . "</p><p class='title' contenteditable=true id='title'>" . strip_tags($title) . "</p><p class='content' id='content' contenteditable='true'>" . strip_tags($content) . "</p><p class='tags' id='tags' contenteditable=true>" . strip_tags($tags) . "</p></div>\n";
}
