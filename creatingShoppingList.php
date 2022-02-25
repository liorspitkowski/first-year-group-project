<?php
	mainFunction(1, "beans");

	//displayInventory();


	// displayInventory();

	// function displayInventory(){
	// 	$conn = connect(true);
	// 	//$result = getFoodId($conn, "testfoo1");

	// 	addInventoryId($conn, 1, 6, 56);

	// }

	// function addInventoryId($conn, $userId, $foodId, $amount){
	// 	$sql = "INSERT INTO inventory (userId, foodId, amount) VALUES (:userId, :foodId, :userId)";
	// 	$stmt = $conn->prepare($sql);

	// 	$stmt->execute([
	// 		'userId' => $userId,
	// 		'foodId' => $foodId,
	// 		'userId' => $userId
	// 	]);
	// }

	//given recipe name (more than one eventually)
	//find recipe id
	//get food ids and quantities
	//get inventory
	//compare with inventory
	//get food names
	//make array and send


	function mainFunction($userId, $name){
		require "DatabaseHandler.php";
		$conn = connect(true);

		$recipeId = getRecipeId($conn, $name);
		
		$foods = getFoodIds($conn, $recipeId);
		$recipeFoodIds = $foods->fetch()['foodId'];
		$recipeQuantities = $foods->fetch()['quantiites'];

		$inventory = getInventory($conn, $userId);
		$inventoryFood = $inventory->fetch()['foodId'];
		$inventoryQuantities = $foods->fetch()['quantiites'];



	}

	function getRecipeId($conn, $name){
		$sql = "SELECT recipeName FROM recipes WHERE recipeName = :name";
		$stmt = $conn->prepare($sql);

		$stmt->execute([
			'name' => $name
		]);
		//could return at different point
		if ($stmt != null){
			while ($row = $stmt->fetch()){
				return $row["recipeId"];
			}
		}
	}

	function getFoodIds($conn, $recipeId){
		$sql = "SELECT foodId, quantity FROM foods WHERE recipeId = :recipeId";
		$stmt = $conn->prepare($sql);

		$stmt->execute([
			'recipeId' => $recipeId
		]);

		return $stmt;
	}

	function getInventory($conn, $userId){
		$sql = "SELECT foodId, quantity FROM inventory WHERE userId = :userId";
		$stmt = $conn->prepare($sql);

		$stmt->execute([
			'userId' => $userId
		]);

		// if ($stmt != null){
		// 	while ($row = $stmt->fetch()){
		// 		//format data
		// 	}
		// }
		return $stmt;
	}

	function getFoodNames($conn, $foods){
		$sql = "SELECT foodName FROM foods WHERE foodId = :foods";
		$stmt = $conn->prepare($sql);

		$stmt->execute([
			'foods' => $foods
		]);

		return $stmt;
	}

	function compareInventory($recipeIng, $inventory){
		//first check if record is there
		//if so, check the quantity
		//update differences
		//return the updated list with the names


	}


?>