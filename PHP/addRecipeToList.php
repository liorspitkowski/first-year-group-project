<?php

	require "DatabaseHandler.php";

	$user = $_POST['userId'];
	$reicpeName = $_POST['recipe'];


	function mainFunction($user){
		$conn = connect(true);

		//get recipe ingredients
		//try to get ingredients from db
		//modify or add items

		//gets the id associated with the given name
		
		$recipe = getRecipeId($conn, $recipeName);

	}

	function getRecipeId($conn, $name){
		$sql = "SELECT recipeId FROM recipes WHERE recipeName = :name";
		$stmt = $conn->prepare($sql);

		$stmt->execute(['name' => $name]);

		$result = $stmt->fetch()['recipeId'];
		return $result;
	}

?>