<?php
	require "DatabaseHandler.php";
	require "generateList.php"; //lots of functions nededed are very similar

	$userId = 14;
	//$userId = $_POST['userId'];
	mainFunction($userId);


	function mainFunction($userId){
		$conn = connect();

		$ingredients = getInformationFromDB($conn, $userId); //been seperated so can be accessed in other files

		//check for empty string returned
		if ($ingredients == ""){
			return ""; //no ingredients CHANGE
		}

		//compare and add ingredients
		addToInventory($conn, $userId, $ingredients[0], $ingredients[1]);
		deleteListRecords($conn, $userId);

		//think about return values????
	}

	function deleteListRecords($conn, $userId){
		$sql = "DELETE FROM recipeList WHERE userId = :user";
		$stmt = $conn->prepare($sql);
		$stmt->execute(['user' => $userId]);
		//now all records are removed
	}

	function addToInventory($conn, $userId, $ingredients, $amounts){
		//loop through each item and add to database
		for ($i = 0; $i < count($ingredients)); $i++){
			//get amount of item in inventory if exists
			$sql = "SELECT amount FROM inventory WHERE userId = :user AND foodId = :food";
			$stmt = $conn->prepare($sql);
			$stmt->execute(['user' => $userId, 'food' => $ingredients[$i]]);
			while ($row = $stmt->fetch()){
				//this executes if anything was actually returned and item should be modified
				$sql = "UPDATE inventory SET amount = :amount WHERE foodId = :food AND userId = :user";
				$stmt = $conn->prepare($sql);
				$stmt->execute(['amount' => ($row['amount'] + $amounts[$i]), 'food' => $ingredients[$i], 'user' => $userId]);
				//check that actually worked?????
				continue; //dont do next bit of code outside of while
			}
			//now add the record to inventory as it doesn't already exist
			$sql = "INSERT INTO inventory (userId, foodId, amount) VALUES (:user, :food, :amount))";
			$stmt = $conn->prepare($sql);
			$stmt->execute(['user' => $userId, 'food' => $ingredients[$i], 'amount' => $amounts[$i]]);
		}
	}

?>