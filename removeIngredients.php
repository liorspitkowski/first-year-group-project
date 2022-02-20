<?php

require "DatabaseHandler.php";

// removes all ingredients of a recipe from a user's inventory
function remove_ingredients($id, $recipeName) {
    $conn = connect(True);

    $recipeId = get_recipeId($conn, $recipeName);

    $ingredientsAndQuantities = get_ingredients_and_quantities($conn, $recipeId);

    // for every ingredient
        // if ingredient in inventory
            // if quantity of ingredient in inventory < quanitity in recipe
                // set quantity to 0
            // else
                // decrease quantity in inventory by quantity in recipe
}

// gets recipeId of recipe from database
function get_recipeId($conn, $recipeName) {
    $sql = "SELECT recipe_id From recipe Where recipe_name = :recipeName";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'recipeName' => $recipeName
    ]);
    return $stmts;
}

// get a list of the ingredients and their quanitites in the recipe
function get_ingredients_and_quantities($conn, $recipeId) {
  $sql = "SELECT food_id, quantity From ingredients Where recipe_id = :recipeId";
  $stmt = $conn->prepare($sql);
  $stmt ->execute([
    'recipeId' => $recipeId
  ]);
  return $stmts;
}


?>
