<?php
	//Written and Maintained by Daniel Makin

	require "DatabaseHandler.php";
	//get parameters
	$userId = $_POST['userId'];
	
	mainFunction($userId);


	function mainFunction($userId){
		//establish connection
		$conn = connect(true);
		$recipes = getRecipeInfo($conn, $userId);
		//gets the recipe names associated with the ids
		$recipes = getRecipeNames($conn, $recipes[0], $recipes[1]);
		echo(returnData($recipes[0], $recipes[1]));
	}

	function getRecipeNames($conn, $Ids, $portions){
		$names = [];
		for ($i = 0; $i < count($Ids); $i++){
			$sql = "SELECT recipeName FROM recipes WHERE recipeId = :recipe";
			$stmt = $conn->prepare($sql);
			$stmt->execute(['recipe' => $Ids[$i]]);
			//will always return a value
			array_push($names, $stmt->fetch()['recipeName']);
		}

		return array($names, $portions);
	}

	function returnData($names, $portions){
		$endString = "";
		for ($i = 0; $i < (count($names)); $i++){
			$endString = $endString . $names[$i] . "#" . $portions[$i] . "#";
		}
		return $endString;
	}


	function getRecipeInfo($conn, $userId){
		$Ids = [];
		$portions = [];
		$sql = "SELECT recipeId, portions FROM shopRecipes WHERE userId = :user";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['user' => $userId]);

		while ($row = $stmt->fetch()){
			array_push($Ids, $row['recipeId']);
			array_push($portions, $row['portions']);
		}

		return array($Ids, $portions);
	}	


?>
