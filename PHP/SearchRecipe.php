<?php

require "DatabaseHandler.php";

function search($search){

  $conn = connect(true);

  $sql = "SELECT recipeName FROM recipes";
  $result = $conn->query($sql);
  $names = explode(" ", $search);

  $results = [];
  while ($row = $result->fetch()){
    $dbNames = explode(" ", $row['recipeName']);
    $total = 0;
    foreach ($names as $name){
      $tests = [];
      foreach ($dbNames as $testName){
        array_push($tests, levenshteinDistance($name, $testName));
      }
      $total += min($tests);
    }
    //echo $row['recipeName']." : ". $total . "\n";
    $results += [$row['recipeName'] => $total];
  }

  asort($results);
  $printReturn = implode(";", array_keys($results));
  echo $printReturn;

}

function levenshteinDistance($w1, $w2){

  $w1 = strtolower($w1);
  $w2 = strtolower($w2);

  $w1len = strlen($w1);
  $w2len = strlen($w2);

  $m = array_fill(0, $w1len+1, array_fill(0, $w2len+1, 0));

  for ($i = 0; $i < $w1len+1; $i++){
    $m[$i][0] = $i;
  }

  for ($i = 0; $i < $w2len+1; $i++){
    $m[0][$i] = $i;
  }

  for ($i = 1; $i <= $w1len; $i++){
    for ($j = 1; $j <= $w2len; $j++){
      $subCost = (substr($w1, $i-1, 1) == substr($w2, $j-1, 1)) ? 0 : 1;
      $m[$i][$j] = min($m[$i-1][$j]+1, $m[$i][$j-1]+1, $m[$i-1][$j-1]+$subCost);
    }
  }
  return $m[$w1len][$w2len];
}

function main(){
  $searchName = $_POST['user_search'];
  search($searchName);
}

//search("beans");
main();

 ?>