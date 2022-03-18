<?php

/* to do
 - add vegi/vegan options
*/

require "DatabaseHandler.php";

//add recipe to database in for (String, int, String[], double[], String[], String)
//example addRecipe("beans on toast", 2, ["toast", "beans"], [2, 400], ["", "g"], "put beans on toast")
function addRecipe($userId, $recipeName, $portions, $timeToMake, $dietry, $ingredients, $amounts, $units, $instructions){

  $conn = connect(true);

  if (!$conn) {
    return "-1 | ERROR : Failed to connect to database";
  }

  if (getrecipeId($conn, $recipeName)->fetch()){
    return "-1 | ERROR : Recipie with name $recipeName already exists";
  }

  //adds data to recipe table
  $sql = "INSERT INTO recipes (recipeName, numIngredients, instructions, portions, timeToMake, vegetarian, vegan, userId)
          VALUES (:recipeName, :num, :instructions, :portions, :timeToMake, :vegetarian, :vegan, :userId)";
  $numIngredients = count($ingredients);
  $vegi = ($dietry[1] != NULL) ? 1 : 0;
  $vegan = ($dietry[0] != NULL) ? 1 : 0;

  $stmt = $conn->prepare($sql);
  $stmt->execute([
    'recipeName' => $recipeName,
    'num' => $numIngredients,
    'instructions' => $instructions,
    'portions' => $portions,
    'timeToMake' => $timeToMake,
    'vegetarian' => $vegi,
    'vegan' => $vegan,
    'userId' => $userId
  ]);

  $recipeId = getrecipeId($conn, $recipeName)->fetch()['recipeId'];

  //adds ingredients to ingredients table
  for ($i = 0; $i < $numIngredients; $i++){

    $ingredient = strtolower($ingredients[$i]);
    $amount = $amounts[$i];
    $unit = strtolower($units[$i]);

    $result = getFoodId($conn, $ingredient);

    //if ingredient already in food table entry is added to ingredients table
    if ($values = $result->fetch()){

      $foodId = $values["foodId"];
      addIngredient($conn, $recipeId, $foodId, $amount);

    }
    //if ingredient not found new entry added to foods table and then entry added to ingredients table
    else{

      $sql = "INSERT INTO foods (foodName, defaultMeasurmentUnits)
              VALUES (:food, :unit)";
      $stmt = $conn->prepare($sql);
      $stmt->execute([
        'food' => $ingredient,
        'unit' => $unit
      ]);

      $foodId = getFoodId($conn, $ingredient)->fetch()['foodId'];

      addIngredient($conn, $recipeId, $foodId, $amount);

    }

  }

  return "1 | Recipe $recipeName successfully added to database";

}

//gets foodId from food table
function getFoodId($conn, $ingredient){

  $sql = "SELECT foodId FROM foods WHERE foodName = :ingredient";
  $stmt = $conn->prepare($sql);

  $stmt->execute([
    'ingredient' => $ingredient
  ]);
  return $stmt;

}

//gets recipeId from recipies table
function getrecipeId($conn, $recipe){

  $sql = "SELECT recipeId FROM recipes WHERE recipeName = :recipe";
  $stmt = $conn->prepare($sql);

  $stmt->execute([
    'recipe' => $recipe
  ]);
  return $stmt;

}

//creates new entry in ingredients table
function addIngredient($conn, $recipeId, $foodId, $amount){

  $sql = "INSERT INTO ingredients (recipeId, foodId, amount)
          VALUES (:recipeId, :foodId, :amount)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([
    'recipeId' => $recipeId,
    'foodId' => $foodId,
    'amount' => $amount
  ]);

}

function main(){

  if (isset($_POST['userId'])){
    $userId = $_POST['userId'];
  }
  else {
    $userId = 0;
  }
  $recipeName = $_POST["recipeName"];
  $portions = $_POST["portions"];
  $dietry = [$_POST["vegan"], $_POST["vegetarian"]];
  $timeToMake = $_POST["timeToCook"];
  $ingredients = [];
  $amounts = [];
  $units = [];
  $instructions = $_POST["instructions"];

  $i = 1;
  while(isset($_POST["ingredient$i"])){
    array_push($ingredients, $_POST["ingredient$i"]);
    array_push($amounts, floatval($_POST["amount$i"]));
    array_push($units, $_POST["unit$i"]);
    $i++;
  }

  //var_dump($dietry);
  echo addRecipe($userId, $recipeName, $portions, $timeToMake, $dietry, $ingredients, $amounts, $units, $instructions);

}

main();

 ?>
