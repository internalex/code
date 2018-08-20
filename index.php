<?php
include_once 'preparepdo.php';
include_once 'getalldata.php';
include_once 'header.php';
include_once 'generatepost.php';
include_once 'users.php';
?>
<!doctype html>
<html lang="en">
	<head>
		<?php echo $styles; ?>
		<script src="main.js"></script>
	</head>
	<body class="container">
		<?php echo getHeader(); ?>
		<br>
		<div class="col-sm-auto main">
			<?php 
				if(isset($_SESSION['username'])) {
					echo '<div class="row">
						<div class="col-sm-auto submit">
							<div>
								<a href="editor.php" class="fse-link"><img src="posticon.png"></a>
								<form action="submit.php" method="post" onsubmit="return copycontent()">
									<div class="hidden">
										<input name="tags" id="tags-input" type="text">
										<input name="content" id="content-input" type="text">
										<input name="title" id="title-input" type="text">
										<input name="username" value="' . $_SESSION['username'] . '">
									</div>
									<button class="btn btn-primary">Submit</button>
									<br>
									<noscript>JavaScript must be enable to create posts</noscript>
								</form>
							</div>
						</div>
						<div class="blogpost col-sm edit">
							<p class="title" contenteditable="true" id="title">Your blogpost\'s title</p>
							<p class="content" contenteditable="true" id="content">Edit this content to create a blogpost</p>
							<p class="tags" contenteditable="true" id="tags">Put some text here that you want your post to be searched with</p>
						</div>
					</div><hr>';
				} else {
					echo '<p class="general"><a href="login.php?ref=index.php">Sign up or login</a> to post</p>';
				}
			?>
			<p class="bigspace"/>
			<p class="general">All Posts:</p>

		<div class="search">
				Filter by text: <input type="text" placeholder="text" class="filter" id="searchbox">
				<button class="filter right" id="searchbtn" onclick="return search()">Search</button>
				<noscript>JavaScript msut be enabled to search through posts</noscript>
			</div>
			<hr>
			<?php
				$ctr = 0;
				foreach ($stmt as $element) {
					if ($ctr != 0) {
						echo "<hr>";
					}
					$username = intToUsername($conn, $element['owner']);
					if(isset($_SESSION['username']) && $username == $_SESSION['username']){
						echo deletable_blogpost($conn, $username, $element['timestamp'], $element['title'], $element['id'], $element['content'], $element['tags']);
					}
					else {
						echo blogpost($conn, $username, $element['timestamp'], $element['title'], $element['id'], $element['content'], $element['tags']);
					}
					$ctr++;	
				}
			?>
		</div>
		
	</body>
</html>
