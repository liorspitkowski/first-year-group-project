//Written and Maintained by Daniel Makin
<?php

	require "DatabaseHandler.php";
	$user = $_POST['userId'];

	mainFunction($user);

	function mainFunction($user){
		$conn = connect(true);

		//returns the recipeIds
		$recipeIds = getRecipeIds($conn, $user);
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

?>