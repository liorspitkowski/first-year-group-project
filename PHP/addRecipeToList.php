//Written and Maintained by Daniel Makin
<?php

	require "DatabaseHandler.php";

	$user = $_POST['userId'];
	$reicpeName = $_POST['recipe'];

	//echo flag returned
	echo(mainFunction($user, $recipeName));


	function mainFunction($user, $recipeName){
		$conn = connect(true);

		$recipe = getRecipeId($conn, $recipeName);

		//checks record doesn't already exist
		if (recordExists($conn, $userId, $recipeId)){
			return "flag-2";
		}

		//record will now be added
		addRecord($conn, $userId, $recipeId);

		//check that record now exists
		if (recordExists($conn, $userId, $recipeId)){
			return "flag-1";
		}else{
			return "flag-0";
		}
	}

	function addRecord($conn, $userId, $recipeId){
		$sql = "INSERT INTO shopRecipes (userId, recipeId) VALUES (:user, :recipe)";
		$stmt = $conn->prepare($sql);

		$stmt->execute(['user' => $userId, 'recipe' => $recipeId]);
	}

	function getRecipeId($conn, $name){
		$sql = "SELECT recipeId FROM recipes WHERE recipeName = :name";
		$stmt = $conn->prepare($sql);

		$stmt->execute(['name' => $name]);

		$result = $stmt->fetch()['recipeId'];
		return $result;
	}

	//used for confirmation and error checking at beginning
	function recordExists($conn, $userId, $recipeId){
		$sql = "SELECT * FROM shopRecipes WHERE userId = :user AND recipeId = :recipe";
		$stmt = $conn->prepare($sql);

		$stmt->execute(['user' => $userId, 'recipe' => $recipeId]);
		while ($row = $stmt->fetch()){
			//should only return one record if any
			return true; //record already exists
		}
		return false; //record doesn't exist
	}

?>