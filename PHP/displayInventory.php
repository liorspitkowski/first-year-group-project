<?php
//change names to db proper ones
	require "DatabaseHandler.php";


	$userId = $_POST['user'];

	mainFunction($userId);

	function mainFunction($user){

		$conn = connect(true);

		$quantity = getFields($conn, $user, 'amount');
		$foodNames = getFoodNames($conn, $user);
		$units = getUnits($conn, $user, 'defaultMeasurmentUnits');

		echo(formatData($foodNames, $quantity, $units));
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

	function getUnits($conn, $user){
		$foodId = getFields($conn, $user, 'foodId');//the food ids fetched

		$array1 = [];
		for ($i = 0; $i < count($foodId); $i++){
			$sql = "SELECT defaultMeasurmentUnits FROM foods WHERE foodId = :foodId";
			$stmt = $conn->prepare($sql);

			$stmt->execute([
				'foodId' => $foodId[$i]
			]);

			if ($stmt != null){
				while ($row = $stmt->fetch()){
					array_push($array1, $row['defaultMeasurmentUnits']);
				}
			}
		}

		return ($array1);
	}

	function formatData($a1, $a2, $a3){
		$data = "";
		for ($i=0; $i < (count($a1) - 1); $i++){
			$data = $data . $a1[$i] . "#" . $a2[$i] . "#" . $a3[$i] . "#";
		}

		if (count($a1) != 0){
			$data = $data . $a1[count($a1) - 1] . "#" . $a2[count($a1) - 1] . "#" . $a3[count($a1) - 1];
		}
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
