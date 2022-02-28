<?php
//change names to db proper ones
	require "DatabaseHandler.php";

	mainFunction(1);

	function mainFunction($user){
		$conn = connect(true);

		$quantity = getFields($conn, $user, 'amount');
		$foodNames = getFoodNames($conn, $user);

		var_dump($foodNames);
		var_dump($quantity);

		echo(formatData($foodNames, $quantity));

		//return data to the hanmin
	}

	function getFields($conn, $user, $fieldName){
		$stmt = getData($conn, $user);

		$array1 = [];
		if ($stmt != null){
			while ($row = $stmt->fetch()){
				array_push($array1, $row[$fieldName]);
			}
		}
		return ($array1);
	}

	function formatData($a1, $a2){
		$data = "";
		for ($i=0; $i < (count($a1) - 1); $i++){
			$data = $data . $a1[$i] . "#" . $a2[$i] . "#";
		}
		$data = $data . $a1[count($a1) - 1] . "#" . $a2[count($a1) - 1];
		return $data;
	}

	function getFoodNames($conn, $user){
		$foodId = getFields($conn, $user, 'foodId');//the food ids fetched

		$array1 = [];
		for ($i = 0; $i < count($foodId); $i++){
			$sql = "SELECT foodName FROM foods WHERE foodId = :foodId";
			$stmt = $conn->prepare($sql);

			$stmt->execute([
				'foodId' => $foodId[$i]
			]);

			if ($stmt != null){
				while ($row = $stmt->fetch()){
					array_push($array1, $row['foodName']);
				}
			}
		}

		return ($array1);
	}

	function getData($conn, $user){
		$sql = "SELECT foodId, amount FROM inventory WHERE userId = :user";
		$stmt = $conn->prepare($sql);

		$stmt->execute([
			'user' => $user
		]);

		return $stmt;
	}


?>