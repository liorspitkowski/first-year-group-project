<?php

require "DatabaseHandler.php";

function getListOfFoods($input){

  $conn = connect();

  $regex = "^$input.*";

  $sql = "SELECT DISTINCT foodName FROM foods WHERE foodName REGEXP :regex";
  $stmt = $conn->prepare($sql);
  $stmt->execute([
    'regex' => $regex
  ]);

  $results = [];

  while ($row = $stmt->fetch())
  {
    array_push($results, $row['foodName']);
  }

  echo (implode(",", $results));

}

function getDefaultUnits($food){

  $conn = connect();

  $sql = "SELECT defaultMeasurmentUnits FROM foods WHERE foodName = :food";
  $stmt = $conn->prepare($sql);
  $stmt->execute([
    'food' => $food
  ]);

  $results = [];

  while ($row = $stmt->fetch())
  {
    array_push($results, $row['defaultMeasurmentUnits']);
  }

  echo (implode(",", $results));
}

function main(){
  if($_POST['function'] == "autofill"){
    getListOfFoods($_POST["input"]);
  }
  else if($_POST['function'] == "defaultUnits"){
    getDefaultUnits($_POST["input"]);
  }
  else{

  }
}

main()
?>