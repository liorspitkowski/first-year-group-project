//Written and Maintained by Daniel Makin
<?php
	
	require "DatabaseHandler.php";

	$userId = $_POST['userId'];
	$recipeId = $_POST['recipeId'];

	mainFunction($userId, $recipeId);

	function mainFunction($userId, $recipeId){
		//removes the item from the table
		$conn = connect(true);
		removeRecord($conn, $userId, $recipeId);

		echo(checkRecordRemoved($conn, $userId, $recipeId));
	}

	function removeRecord($conn, $userId, $recipeId){
		$sql = "DELETE FROM shopRecipes WHERE userId = :user AND recipeId = :recipe";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['user' => $userId, 'recipe' => $recipeId]);
	}

	function checkRecordRemoved($conn, $userId, $recipeId){
		$sql = "SELECT * FROM shopRecipes WHERE userId = :user AND recipeId = :recipe";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['user' => $userId, 'recipe' => $recipeId]);

		//check if successful
		while ($row = $stmt->fetch()){
			return "flag-0";
		}
		//this means nothing was returned
		return "flag-1";
	}

?>