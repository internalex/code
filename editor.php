<?php
include_once 'header.php';
include_once 'users.php';

?>
<!doctype html>
<html>
	<head>
		<?php echo $styles; ?>
		<script src="main.js"></script>
	</head>
	<body>
		<div class="container">
			<?php echo getHeader(); ?>
			<br>
			<div class="editor">
				<p class="general inline-block">Blogpost title</p><p id="title" class="title bord inline-block" contenteditable="true">Your blogpost's title</p>	
				<br>
				<p class="general inline-block">Blogpost content</p><p id="content" class="content bord inline-block" contenteditable="true">Your blogpost's content</p>	
				<br>
				<p class="general inline-block">Blogpost search tags</p><p id="tags" class="tags-modified bord inline-block" contenteditable="true">Your blogpost's tags</p>	
				<br>
				<form action='submit.php' method='post' onsubmit='return copycontent()'>
					<div class='hidden'>
						<input name="username" value="<?php echo $_SESSION['username']; ?>">	
						<input name="title" id="title-input">
						<input name="content" id="content-input">
						<input name="tags" id="tags-input">
					</div>
					<button class='btn btn-primary'>Submit</button>
				</form>
			</div>
		</div>
	</body>
</html>
