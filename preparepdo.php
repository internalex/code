<?php


$conn = null;
try {
	$conn = new PDO("mysql:host=localhost;dbname=blogposts", "appins", "");
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo $e->getMessage();
}
