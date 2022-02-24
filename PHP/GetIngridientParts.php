<?php

require "DatabaseHandler.php";

function getListOfFoods($input){

  $conn = connect(true);

  $regex = "^$input.*";

  $sql = "SELECT foodName FROM foods WHERE foodName REGEXP :regex";
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

function main(){
  if($_POST['function'] == "autofill"){
    getListOfFoods($_POST["input"]);
  }
  else if($_POST['function'] == "defaultUnits"){
    getListOfFoods($_POST["input"]);
  }
  else{

  }
}

main()
?>