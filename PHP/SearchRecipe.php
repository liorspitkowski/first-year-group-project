<?php

require "DatabaseHandler.php";

//search based on recipe name
function search($search, $vegi, $vegan){

  $conn = connect(true);

  $sql = "SELECT recipeId, recipeName, vegetarian, vegan FROM recipes";
  $result = $conn->query($sql);
  $names = explode(" ", $search);

  $results = [];
  while ($row = $result->fetch()){
    if (($vegi && ($row['vegetarian'] != 1)) || ($vegan && ($row['vegan'] != 1))){
      continue;
    }
    $dbNames = explode(" ", $row['recipeName']);
    $score = 0;

    foreach ($names as $name){
      //scores how well recipe matches search
      if ($search != ""){
        $tests = [];
        foreach ($dbNames as $testName){
          array_push($tests, levenshteinDistance($name, $testName));
        }
        $score += min($tests);
      }
    }


    $results += [$row['recipeName'] => $score];
  }

  asort($results);
  $printReturn = implode(";", array_keys($results));
  echo $printReturn;

}

function searchInv($uid, $vegi, $vegan){

  $conn = connect(true);

  $sql = "SELECT recipeId, vegetarian, vegan FROM recipes";
  $result = $conn->query($sql);
  $names = explode(" ", $search);

  $results = [];
  while ($row = $result->fetch()){
    if (($vegi && ($row['vegetarian'] != 1)) || ($vegan && ($row['vegan'] != 1))){
      continue;
    }
    $recipeName = $row['recipeName'];
    $score = isInvetory($conn, $recipeName, $uid);

    $results += [$row['recipeName'] => $score];
  }

  sort($results);
  $printReturn = implode(";", array_keys($results));
  echo $printReturn;

}

//calculates how well recipe matches what is in inventory
function inIventory($conn, $recipeid, $uid){

  //fetches data from inventory
  $sql = "SELECT foodId, amount FROM inventory WHERE userId = :userid";
  $stmt = $conn->prepare($sql);
  $stmt->execute([
    'userid' => $uid
  ]);

  $foods = [];

  while($row = $stmt->fetch()){
    $foods += [$row['foodId'] => $row['amount']];
  }

  //fetches data of recipe
  $sql = "SELECT foodId,amount FROM ingredients WHERE recipeId = :recipeId";
  $stmt = $conn->prepare($sql);
  $stmt->execute([
    'recipeId' => $recipeid
  ]);

  $ingridients = [];

  while($row = $stmt->fetch()){
    $ingridients += [$row['foodId'] => $row['amount']];
  }

  $score = 100.0;
  foreach ($ingridients as $ingridient => $amount){
    if (isset($foods[$ingridient])){
      if ($foods[$ingridient] < $amount){
        $score -= ($amount - $foods[$ingridient])/$amount * 100/count($ingridients);
        echo $amount - $foods[$ingridient] . "\n";
      }
    }
    else {
      $score -= 100/count($ingridients);
    }
    echo $score . "\n";
  }

  echo http_build_query($foods) . "\n";
  echo http_build_query($ingridients) . "\n";
  echo $score . "%\n";

}

//checks for matching dietry requirments
function matchesDietry($conn, $recipeid, $dietry){

  //fetches dietry info
  $sql = "SELECT $dietry FROM recipes WHERE recipeId = :recipeId";
  $stmt = $conn->prepare($sql);
  $stmt->execute([
    'recipeId' => $recipeid
  ]);

  if ($result = $stmt->fetch()){
    return ($result[$dietry] == 1);
  }
  return false;

}

//calculates how different two words are
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
  $searchByInventory = $_POST['inv_search'];
  $userId = $_POST['user_id'];
  $vegi = isset($_POST['filter1']);
  $vegan = isset($_POST['filter2']);

  if ($searchByInventory != NULL){
    echo "inv";
    searchInv($userId, $vegi, $vegan);
  }
  else{
    echo "name";
    search($searchName, $vegi, $vegan);
  }
}

main();

 ?>