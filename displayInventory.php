<?php
//change names to db proper ones
	require "DatabaseHandler.php";

	function displayInventory(){
		$conn = connect(true);
	}

	function getFoodId($conn, $ingredient){

	  $sql = "SELECT foodName FROM foods WHERE foodId = :ingredient";
	  $stmt = $conn->prepare($sql);

	  //change this
	  $stmt->execute([
	    'ingredient' => $ingredient
	  ]);
	  return $stmt;
	}

	function getInventoryFoodNames($conn, $userId){
		//$sql = "SELECT food_id, quantity FROM recipes WHERE user_id = :userId";
		$sql = "SELECT food_name FROM food WHERE food_id = (SELECT food_id FROM Inventory WHERE user_id = :userId)";
		$stmt = $conn->prepare($sql);

		$stmt->execute([
		  'userId' => $userId
		]);

		return $stmt;
	}

	function getQuantities($conn, $userId){
		$sql = "SELECT quantity FROM Inventory WHERE user_id = :userId";
		$stmt = $conn->prepare($sql);

		$stmt->execute([
		  'userId' => $userId
		]);

		return $stmt;
	}

	function main($user){
		$conn = connect(true);
		$data = getData($conn, $user);

		$foodNames = $data->fetch()['foodName'];
		$quantity = $data->fetch()['quantity'];

		//return data to the hanmin
	}

	function getData($conn, $user){
		$sql = "SELECT foodName, quantity FROM foods WHERE userName = :user";
		$stmt = $conn->prepare($sql);

		$stmt->execute([
			'user' => $user
		]);

		return stmt;
	}


?>