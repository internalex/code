<?php
include_once 'preparepdo.php';
include_once 'searchdata.php';
include_once 'header.php';
include_once 'generatepost.php';
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
			<div class="search">
				Filter by text: <input type="text" placeholder="text" class="filter" id="searchbox" value="<?php echo $_GET["search"]; ?>">
				<button class="filter right" id="searchbtn" onclick="return search()">Search</button>
			</div>
			<hr>
			<?php
				$ctr = 0;
				foreach ($stmt as $element) {
					if ($ctr != 0) {
						echo "<hr>";
					}
					if (intToUsername($conn, $element['owner']) == $_SESSION['username']){
						echo deletable_blogpost($conn, intToUsername($conn, $element['owner']), $element['timestamp'], $element['title'], $element['id'], $element['content'], $element['tags']);
					}
					else {
						echo blogpost($conn, intToUsername($conn, $element['owner']), $element['timestamp'], $element['title'], $element['id'], $element['content'], $element['tags']);
					}
					$ctr++;
				}
				
			?>
		</div>
	</body>
</html>
