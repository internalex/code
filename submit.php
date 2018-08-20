<?php
include_once 'insertcontent.php';
include_once 'header.php';
include_once 'generatepost.php';
?>
<!doctype html>
<html>
	<head>
		<?php echo $styles; ?>
	</head>
	<body class="container">
		<?php echo getHeader(); ?>
		<p class="thanks">Thank you for your submission!
		<a href="/">Click here to go back to the main page</a></p>
		<hr>
		 <?php
			if($success == true){
				$date = date('Y-m-d H:i:s', time());	
				echo(nolike_partial_deletable_blogpost($conn, $_POST['username'], $date, $_POST['title'], $id, $_POST['content'], $_POST['tags']));
			}
			else {
				echo("<p class='general'>Blogpost was not submitted because it was unedited.</p>");
			}
		 ?>
	</body>
</html>


