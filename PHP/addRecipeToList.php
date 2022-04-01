<?php
	//Written and Maintained by Daniel Makin

	require "DatabaseHandler.php";

	$userId = $_POST['user_id'];
	$recipeId = $_POST['recipe_id'];
	$portions = $_POST['portions'];

	//echo flag returned
	echo(mainFunction($userId, $recipeId, $portions));


	function mainFunction($userId, $recipeId, $portions){
		$conn = connect(true);

		//checks record doesn't already exist
		if (recordExists($conn, $userId, $recipeId)){
			return "flag=0";
		}

		//record will now be added
		addRecord($conn, $userId, $recipeId, $portions);

		//check that record now exists
		if (recordExists($conn, $userId, $recipeId)){
			return "flag=1";
		}else{
			return "flag=0";
		}
	}

	function addRecord($conn, $userId, $recipeId, $portions){
		$sql = "INSERT INTO shopRecipes (userId, recipeId, portions) VALUES (:user, :recipe, :portions)";
		$stmt = $conn->prepare($sql);

		$stmt->execute(['user' => $userId, 'recipe' => $recipeId, 'portions' => $portions]);
	}

	// function getRecipeId($conn, $name){
	// 	$sql = "SELECT recipeId FROM recipes WHERE recipeName = :name";
	// 	$stmt = $conn->prepare($sql);

	// 	$stmt->execute(['name' => $name]);

	// 	$result = $stmt->fetch()['recipeId'];
	// 	return $result;
	// }

	//used for confirmation and error checking at beginning
	//doesn't need to check portions
	function recordExists($conn, $userId, $recipeId){
		$sql = "SELECT * FROM shopRecipes WHERE userId = :user AND recipeId = :recipe";
		$stmt = $conn->prepare($sql);

		$stmt->execute(['user' => $userId, 'recipe' => $recipeId]);
		$row = $stmt->fetch();
		while ($row != null){
			//should only return one record if any
			return true; //record already exists
		}
		return false; //record doesn't exist
	}

?>
