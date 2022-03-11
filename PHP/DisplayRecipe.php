<?php

require "DatabaseHandler.php";

$name = $_GET['recipeName'];
//$name = "Chicken Korma";

$conn = connect();

$sql = "SELECT * FROM recipes WHERE recipeName = :name";
$stmt = $conn->prepare($sql);
$stmt->execute([
  'name' => $name
]);

if($row = $stmt->fetch()){
  $id = $row['recipeId'];
  $instructions = $row['instructions'];
  $foods = [];
  echo $name . "\n";
  echo "portions : " . $row['portions'] . "\n";
  $sql = "SELECT foods.*, ingredients.amount from ingredients JOIN foods ON ingredients.foodId=foods.foodId and ingredients.recipeId = $id";
  $result = $conn->query($sql);
  while($food = $result->fetch()){
    $foodStr = $food['amount'] . $food['defaultMeasurmentUnits'] . " " . $food['foodName'] . "\n";
    array_push($foods, $foodStr);
  }
  echo $instructions;
}
else{
  echo "404 not found";
}

 ?>