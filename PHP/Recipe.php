<?php

require "DatabaseHandler.php";

//add recipe to database in for (String, String[], double[], String[], String)
//example add_recipe("beans on toast", ["toast", "beans"], [2, 400], ["", "g"], "put beans on toast")
function add_recipe($recipeName, $ingredients, $amounts, $units, $instructions){

  $conn = connect(True);

  //adds data to recipe table
  $sql = "INSERT INTO recipes (recipeName, numIngredients, instructions)
          VALUES (:recipeName, :num, :instructions)";
  $numIngredients = count($ingredients);

    $stmt = $conn->prepare($sql);
    $stmt->execute([
      'recipeName' => $recipeName,
      'num' => $numIngredients,
      'instructions' => $instructions
    ]);

  $recipeId = getrecipeId($conn, $recipeName)->fetch()['recipeId'];
  echo($recipeId . "\n");

  //adds ingredients to ingredients table
  for ($i = 0; $i < $numIngredients; $i++){

    $ingredient = strtolower($ingredients[$i]);
    $amount = $amounts[$i];
    $unit = strtolower($units[$i]);

    $result = getFoodId($conn, $ingredient);

    //if ingridient already in food table entry is added to ingredients table
    if ($values = $result->fetch()){

      $foodId = $values["foodId"];
      //echo($foodId . "\n");
      addIngredient($conn, $recipeId, $foodId, $amount);

    }
    //if ingridient not found new entry added to foods table and then entry added to ingredients table
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


}

function getFoodId($conn, $ingredient){

  $sql = "SELECT foodId FROM foods WHERE foodName = :ingredient";
  $stmt = $conn->prepare($sql);

  $stmt->execute([
    'ingredient' => $ingredient
  ]);
  return $stmt;

}

function getrecipeId($conn, $recipe){

  $sql = "SELECT recipeId FROM recipes WHERE recipeName = :recipe";
  $stmt = $conn->prepare($sql);

  $stmt->execute([
    'recipe' => $recipe
  ]);
  return $stmt;

}

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

add_recipe("beans on toast", ["toast", "beans"], [2, 400], ["slices", "g"], "put beans on toast");

 ?>