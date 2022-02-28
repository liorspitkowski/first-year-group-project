<?php
	require "DatabaseHandler.php";

	mainFunction(1);

	function getRecipeId($conn, $recipeName){
		$sql = "SELECT recipeId FROM recipes WHERE recipeName = :recipeName";
		$stmt = $conn->prepare($sql);

		$stmt->execute(['recipeName' => $recipeName]);

		return $stmt->fetch()['recipeId'];
	}
	function getInventory($conn, $userId){
		$sql = "SELECT foodId, amount FROM inventory WHERE userId = :userId";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['userId' => $userId]);

		//now look through records
		$Ids = [];
		$amount = [];
		if ($stmt != null){
			while ($row = $stmt->fetch()){
				array_push($Ids, $row['foodId']);
				array_push($amount, $row['amount']);
			}
		}

		return array($Ids, $amount);
	}	
	function getIngredients($conn, $userId, $recipeId){
		$sql = "SELECT foodId, amount FROM ingredients WHERE recipeId = :recipeId";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['recipeId' => $recipeId]);

		//now look through records
		$Ids = [];
		$amount = [];
		if ($stmt != null){
			while ($row = $stmt->fetch()){
				array_push($Ids, $row['foodId']);
				array_push($amount, $row['amount']);
			}
		}

		return array($Ids, $amount);
	}
	function compareLists($conn, $userId, $ingredients){

	}


	function mainFunction($user){
		$conn = connect(true);

		$recipeId = getRecipeId($conn, "beans on toast");
		//$inventory = getInventory($conn, $user);
		$ingredients = getIngredients($conn, $user, $recipeId);

		$finalList = compareLists($conn, $userId, $ingredients)
	}


?>