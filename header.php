<?php
session_start();

function getHeader() {
	if (isset($_SESSION["username"])) {
		return '<div class="header">
	                        <p class="left">
	                                <a href="/">Generic Blogging Platform</a>
	                        </p>
	                        <p class="right">
					<span>Logged in as <a href="profile.php?username=' . $_SESSION["username"] . '">' . $_SESSION["username"] . '</a></span>
	                                <a href="login.php?reason=logout&ref=' . urlencode(basename($_SERVER['REQUEST_URI'])) . '">Log Out</a>
	                        </p>
	                </div><br><br><br>';
	}
	return '<div class="header">
                        <p class="left">
                                <a href="/">Generic Blogging Platform</a>
                        </p>
                        <p class="right">
                                <a href="login.php?ref=' . urlencode(basename($_SERVER['REQUEST_URI'])) . '">Login / Sign Up</a>
                        </p>
                </div><br><br><br>';

}

$styles = '<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Oswald" rel="stylesheet">
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
                <link href="styles.css" rel="stylesheet">';

