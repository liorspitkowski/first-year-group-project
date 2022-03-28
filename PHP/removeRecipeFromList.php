<?php
	//Written and Maintained by Daniel Makin
	require "DatabaseHandler.php";

	$userId = $_POST['user_id'];
	$recipeId = $_POST['recipe_id'];

	mainFunction($userId, $recipeId);

	function mainFunction($userId, $recipeName){
		//removes the item from the table
		$conn = connect(true);
		//get recipeid associated
		$recipeId = getRecipeId($conn, $recipeName);
		//check record exists
		if (checkIfInDatbase($conn, $userId, $recipeId) == false){
			return "flag=1";
		}

		removeRecord($conn, $userId, $recipeId);

		echo(checkRecordRemoved($conn, $userId, $recipeId));
	}

	function getRecipeId($conn, $recipeName){
		$sql = "SELECT recipeId FROM recipes WHERE recipeName = :recipe";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['recipe' => $recipeName]);
		return $stmt->fetch()['recipeId'];
	}

	function removeRecord($conn, $userId, $recipeId){
		$sql = "DELETE FROM shopRecipes WHERE userId = :user AND recipeId = :recipe";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['user' => $userId, 'recipe' => $recipeId]);
	}

	function checkIfInDatbase($conn, $userId, $recipeId){
		$sql = "SELECT * FROM shopRecipes WHERE userId = :user AND recipeId = :recipe";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['user' => $userId, 'recipe' => $recipeId]);

		while ($row = $stmt->fetch()){
			return true;
		}
		return false;
	}

	function checkRecordRemoved($conn, $userId, $recipeId){
		$sql = "SELECT * FROM shopRecipes WHERE userId = :user AND recipeId = :recipe";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['user' => $userId, 'recipe' => $recipeId]);

		//check if successful
		while ($row = $stmt->fetch()){
			return "flag=0";
		}
		//this means nothing was returned
		return "flag=2";
	}

?>
