<?php
	require "DatabaseHandler.php";
	// $user = $_POST['userId'];
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
	function formatData($a1, $a2){
		$data = "";
		for ($i=0; $i < (count($a1) - 1); $i++){
			$data = $data . $a1[$i] . "#" . $a2[$i] . "#";
		}

		if (count($a1) != 0){
			$data = $data . $a1[count($a1) - 1] . "#" . $a2[count($a1) - 1];
		}
		return $data;
	}
	function mainFunction($user){
		$conn = connect(true);

		$recipeId = getRecipeId($conn, "Chicken Korma");
		$ingredients = getIngredients($conn, $user, $recipeId);

		$finalList = compareLists($conn, $user, $ingredients);
		$finalList[0] = getIngredientNames($conn, $finalList[0]);
		
		echo(formatData($finalList[0], $finalList[1]));
	}
?>