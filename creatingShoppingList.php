<?php
	require "DatabaseHandler.php";

	function displayInventory(){
		$conn = connect(true);
		$sql = "SELECT " 
	}

	function getFoodId($conn, $ingredient){

	  $sql = "SELECT foodId FROM foods WHERE foodName = :ingredient";
	  $stmt = $conn->prepare($sql);

	  $stmt->execute([
	    'ingredient' => $ingredient
	  ]);
	  return $stmt;
	}

	function getFoodIdFromRecipe($conn, $recipeId){
		$sql = "SELECT food_id, food_id FROM ingredients WHERE recipe_id = :recipeId";
		$stmt = $conn->prepare($sql);

	  $stmt->execute([
	    'ingredient' => $ingredient
	  ]);
	  return $stmt;

	  //then increment the data in the calling function after searching for each element
	}
?>