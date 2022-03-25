//Written and Maintained by Daniel Makin
<?php
	
	require "DatabaseHandler.php";

	$userId = $_POST['user_id'];
	$recipeId = $_POST['recipe_id'];

	mainFunction($userId, $recipeId);

	function mainFunction($userId, $recipeId){
		//removes the item from the table
		$conn = connect(true);
		//check record exists
		if (checkIfInDatbase() == false){
			return "flag=1";
		}


		removeRecord($conn, $userId, $recipeId);

		echo(checkRecordRemoved($conn, $userId, $recipeId));
	}

	function removeRecord($conn, $userId, $recipeId){
		$sql = "DELETE FROM shopRecipes WHERE userId = :user AND recipeId = :recipe";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['user' => $userId, 'recipe' => $recipeId]);
	}

	function checkIfInDatbase($conn, $userId, $recipeId){
		$sql = "SELECT * FROM recipeList WHERE userId = :user AND recipeId = :recipe";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['user' => $userId, 'recipe' => $recipeId])

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
