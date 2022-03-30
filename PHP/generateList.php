<?php
	//Written and Maintained by Daniel Makin

	require "DatabaseHandler.php";
	$userId = $_POST['userId'];
	//$userId = 14;
	echo(mainFunction($userId));

	function mainFunction($userId){
		$conn = connect();

		$ingredients = getInformationFromDB($conn, $userId); //been seperated so can be accessed in other files

		//check for empty string returned
		if ($ingredients == ""){
			return ""; //no ingredients
		}

		//now the names and units are retrieved
		$ingredients = getIngredientNames($conn, $ingredients[0], $ingredients[1]);

		$endString = formatData($ingredients[0], $ingredients[1], $ingredients[2]);

		//then returned to user
		return $endString;

	}

	function getInformationFromDB($conn, $userId){
		$recipeIds = getRecipeIds($conn, $userId);
		//return nothing
		if (sizeof($recipeIds) == 0){
			return "";
		}

		//commented out for test purposes

		$portions = getPortions($conn, $userId);
		$ingredients = getAllIngredients($conn, $recipeIds, $portions);


		$ingredients = compareListsWithInventory($conn, $userId, $ingredients[0], $ingredients[1]);

		return array($ingredients[0], $ingredients[1]);
	}

	function getPortions($conn, $userId){
		$portions = [];
		$sql = "SELECT portions FROM shopRecipes WHERE userId = :user";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['user' => $userId]);

		while ($row = $stmt->fetch()){
			array_push($portions, $row['portions']);
		}
		return $portions;
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

	function getAllIngredients($conn, $recipeIds, $portions){
		//can not return
		if (sizeof($recipeIds) == 0){
			return;
		}

		//get the first ingredients as these don't need to be compared
		$multiplier = getPortionMultiplier($conn, $recipeIds[0], $portions[0]);
		$items = getRecipeIngredients($conn, $recipeIds[0], $multiplier);

		for ($i=1; $i < sizeof($recipeIds); $i++){
			//this gets the amount of times the recipe is needed
			$multiplier = getPortionMultiplier($conn, $recipeIds[$i], $portions[$i]);
			//get next list of items
			$newItems = getRecipeIngredients($conn, $recipeIds[$i], $multiplier);

			//now compare lists
			$items = compareLists($items[0], $items[1], $newItems[0], $newItems[1]);
		}

		//round due to wierd numbers produced by portions multiplier
		$items = roundAmounts($items);
		return $items;
	}

	function roundAmounts($ingredients){
		for ($i = 0; $i < count($ingredients[1]); $i++){
			//then round every value up for ingredients to nearest one?
			$ingredients[1][$i] = ceil($ingredients[1][$i]);
		}

		return $ingredients;
	}

	function getPortionMultiplier($conn, $recipeId, $portions){
		//get original portions amount
		$sql = "SELECT portions FROM recipes WHERE recipeId = :recipe";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['recipe' => $recipeId]);
		//will only return one value, should never be null
		$recipePortions = $stmt->fetch()['portions'];

		return ($portions / $recipePortions);
	}

	function compareLists($aIng, $aAmounts, $bIng, $bAmounts){
		//a is the original list, b is the new list
		for ($i = 0; $i < sizeof($bIng); $i++){
			//loops through each item in new list

			//search new list for index
			$index = array_search($bIng[$i], $aIng);
			//echo $index;

			if ($index != false){
				//modify the index to add the new items
				$aAmounts[$index] += $bAmounts[$i];
			}else{
				array_push($aIng, $bIng[$i]);
				array_push($aAmounts, $bAmounts[$i]);
			}
		}
		return array($aIng, $aAmounts);
	}

	function getRecipeIngredients($conn, $recipeId, $multiplier){
		$Ids = [];
		$amounts = [];
		$sql = "SELECT foodId, amount FROM ingredients WHERE recipeId = :recipe";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['recipe' => $recipeId]);

		while ($row = $stmt->fetch()){
			array_push($Ids, $row['foodId']);
			array_push($amounts, $row['amount']);
		}

		//this adjusts for amount of portions

		for ($i = 0; $i < count($amounts); $i++){
			$amounts[$i] = $amounts[$i] * $multiplier;
		}

		return array($Ids, $amounts);
	}

	function compareListsWithInventory($conn, $userId, $ingredients, $amounts){

		for ($i = 0; $i < count($ingredients); $i++){
			//try to select the ingredient in the inventory list
			$sql = "SELECT amount FROM inventory WHERE userId = :userId AND foodId = :foodId";
			$stmt = $conn->prepare($sql);
			$stmt->execute(['userId' => $userId, 'foodId' => $ingredients[$i]]);
			//checks if the user has any
			while ($row = $stmt->fetch()){
				$amounts[$i] -= $row['amount']; //will only have one record
			}
		}
		$newAmounts = [];
		$newIngredients = [];

		for ($i = 0; $i < count($ingredients); $i++){
			//check each element whether it is below or equal to 0
			if ($amounts[$i] > 0){
				array_push($newAmounts, $amounts[$i]);
				array_push($newIngredients, $ingredients[$i]);
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

	function removeUnitVariableNames($ingredients, $units){
		for ($i = 0; $i < count($ingredients); $i++){
			//remove last letter
			$last = substr($ingredients[$i], -1);
			if ($last == "s"){
				$ingredients[$i] = substr($ingredients, 0, -1) . "(s)";
			}else{
				$ingredients[$i] = $ingredients[$i] . "(s)";
			}

			//remove unit from units array
			$units[$i] = " ";
		}
	}
?>
