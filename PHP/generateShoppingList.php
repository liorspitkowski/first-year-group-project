//Written and Maintained by Daniel Makin
<?php

	require "DatabaseHandler.php";
	$user = $_POST['userId'];

	echo(mainFunction($user));

	function mainFunction($user){
		$conn = connect(true);

		//returns the recipeIds
		$recipeIds = getRecipeIds($conn, $user);

		//return nothing
		if (sizeof($recipeIds) == 0){
			return "";
		}

		//commented out for test purposes
		//$ingredients = getAllIngredients($conn, $recipeIds);

		$ingredients = compareListsWithInventory($conn, $userId, $ingredients);

		//now the names and units are retrieved
		$ingredients = getIngredientNames($conn, $ingredients[0], $ingredients[1]);

		$endString = formatData($ingredients[0], $ingredients[1], $ingredients[2]);

		//then returned to user
		return $endString;

	}

	function getIngredientNames($conn, $Ids, $amounts){
		$arrayNames = [];
		$arrayUnits = [];
		$newIds = $Ids[0];
		for ($i = 0; $i < count($Ids); $i++){
			$sql = "SELECT foodName, defaultMeasurmentUnits FROM foods WHERE foodId = :foodId";
			$stmt = $conn->prepare($sql);

			$stmt->execute([
				'foodId' => $Ids[$i]
			]);

			if ($stmt != null){
				while ($row = $stmt->fetch()){
					array_push($arrayNames, $row['foodName']);
					array_push($arrayUnits, $row['defaultMeasurmentUnits']);
				}
			}
		}
		//return $arrayNames;
		return array($arrayNames, $amounts, $arrayUnits);
	}

	function getRecipeIds($conn, $user){
		$Ids = [];
		$sql = "SELECT recipeId FROM shopRecipes WHERE userId = :user";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['user' => $user]);

		//check this is correct
		while ($row = $stmt->fetch()){
			array_push($Ids, $row['recipeId']);
		}

		return $Ids;
	}

	function getAllIngredients($conn, $recipeIds){
		//can not return
		if (sizeof($recipeIds)) == 0{
			return;
		}

		//get the first ingredients as these don't need to be compared
		$items = getRecipeIngredients($recipeIds);

		for ($i=1; $i < sizeof($recipeIds)){
			//get next list of items
			$newItems = getRecipeIngredients($recipeIds[$i]);

			//now compare lists
			$items = compareLists($items, $newItems);
		}

		return $items;
	}

	function compareLists($a, $b){
		//a is the original list, b is the new list
		for ($i = 0; $i < sizeof($b[0])){
			//loops through each item in new list

			//search new list for index
			$index = array_search($b[0][$i], $a[0];

			if ($index != -1){
				//modify the index to add the new items
				$a[1][$index] += $b[1][$i];
			}
		}
		return $a;
	}

	function getRecipeIngredients($conn, $recipeId){
		$Ids = [];
		$amounts = [];
		$sql = "SELECT foodId, amount FROM ingredients WHERE recipeId = :recipe";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['recipe' => $recipeId]);

		while ($row = $stmt->fetch()){
			array_push($Ids, $row['foodId']);
			array_push($amounts, $row['amount']);
		}

		return array($Ids, $amounts);
	}

	function compareListsWithInventory($conn, $userId, $ingredients){
		for ($i = 0; $i <= count($ingredients); $i++){
			//try to select the ingredient in the inventory list
			$sql = "SELECT amount FROM inventory WHERE userId = :userId AND foodId = :foodId";
			$stmt = $conn->prepare($sql);
			$stmt->execute(['userId' => $userId, 'foodId' => $ingredients[0][$i]]);
			//checks if the user has any
			while ($row = $stmt->fetch()){
				$ingredients[1][$i] -= $row['amount']; //will only have one record
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

	function formatData($names, $amounts, $units){
		$data = "";
		for ($i=0; $i < (count($names) - 1); $i++){
			$data = $data . $names[$i] . "#" . $amounts[$i] . "#" . $units[$i] . "#";
		}

		if (count($names) != 0){
			$data = $data . $names[count($names) - 1] . "#" . $amounts[count($names) - 1] . "#" . $units[count($names) - 1];
		}
		return $data;
	}
?>