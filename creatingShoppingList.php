<?php
	require "DatabaseHandler.php";
	// $user = $_POST['userId'];
	// $recipeName = $_POST['recipeId'];

	//mainFunction($user, $recipeName);
	mainFunction(1, "Chicken Korma");

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
		for ($i = 0; $i < count($ingredients); $i++){
			//try to select the ingredient in the inventory list
			$sql = "SELECT amount FROM inventory WHERE userId = :userId AND foodId = :foodId";
			$stmt = $conn->prepare($sql);
			$stmt->execute(['userId' => $userId, 'foodId' => $ingredients[0][$i]]);
			//checks if the user has any
			if ($stmt == true){
				$ingredients[1][$i] -= ($stmt->fetch()['amount']);
			}

		}
		$newAmounts = [];
		$newIngredients = [];


		for ($i = 0; $i < count($ingredients[0]); $i++){
			//check each element whether it is below or equal to 0
			if ($ingredients[1][$i] > 0){
				array_push($newAmounts, $ingredients[1][$i]);
				array_push($newIngredients, $ingredients[0][$i]);
			}
		}

		//then replace the new array

		return array($newIngredients, $newAmounts);
	}
	function getIngredientNames($conn, $Ids){
		$arrayNames = [];
		for ($i = 0; $i < count($Ids); $i++){
			$sql = "SELECT foodName FROM foods WHERE foodId = :foodId";
			$stmt = $conn->prepare($sql);

			$stmt->execute([
				'foodId' => $Ids[$i]
			]);

			if ($stmt != null){
				while ($row = $stmt->fetch()){
					array_push($arrayNames, $row['foodName']);
				}
			}
		}
		return $arrayNames;
	}
	function formatData($names, $amounts){
		$data = "";
		for ($i=0; $i < (count($names) - 1); $i++){
			$data = $data . $names[$i] . "#" . $amounts[$i] . "#";
		}

		if (count($names) != 0){
			$data = $data . $names[count($names) - 1] . "#" . $amounts[count($names) - 1];
		}
		return $data;
	}
	function mainFunction($user, $recipe){
		$conn = connect(true);

		$recipeId = getRecipeId($conn, $recipe);
		$ingredients = getIngredients($conn, $user, $recipeId);

		$finalList = compareLists($conn, $user, $ingredients);
		$finalList[0] = getIngredientNames($conn, $finalList[0]);
		
		echo(formatData($finalList[0], $finalList[1]));
	}
?>
