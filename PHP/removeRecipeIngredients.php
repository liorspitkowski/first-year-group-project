<?php

require "DatabaseHandler.php";

// removes all ingredients of a recipe from a user's inventory
function remove_ingredients() {
    // retrieves variables from POST
    $id = $_POST["id"];
    $recipeName = $_POST["recipeName"];

    $conn = connect(True); // connects to database

    $recipeId = get_recipeId($conn, $recipeName);

    $ingredientsAndQuantities = get_ingredients_and_quantities_recipe($conn, $recipeId);
    $recipeIngredientsList = $ingredientsAndQuantities['foodId'];
    $recipeAmountsList = $ingredientsAndQuantities['amount'];

    // for every ingredient in the recipe, get the amount in the user's inventory and decrease the amount of the ingredient in the users' inventory, if theres not enough, set to 0
    for ($i = 0; $x < count($recipeIngredientsList); $i++) {
        $amountInInventory = get_amount_from_inventory($conn, $userId, $recipeIngredientsList[$i]);
        if ($amountInInventory != null) {
            if ($amountInInventory >= $recipeAmountsList[$i]) {
                decrease_amount_from_inventory($conn, $userId, $recipeIngredientsList[$i], $amountInInventory - $recipeAmountsList[$i]);
            } else {
                decrease_amount_from_inventory($conn, $userId, $recipeIngredientsList[$i], 0);
            }
        }
    }

// gets recipeId of recipe from database
function get_recipeId($conn, $recipeName) {
    $sql = "SELECT recipeId From recipe Where recipeName = :recipeName";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'recipeName' => $recipeName
    ]);
    return $stmts['recipeId'];
}

// get a list of the ingredients and their quanitites in the recipe
function get_ingredients_and_quantities_recipe($conn, $recipeId) {
    $sql = "SELECT foodId, amount From ingredients Where recipeId = :recipeId";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'recipeId' => $recipeId
    ]);
    return mysql_fetch_assoc($stmts);
}

// fetches amount of ingredient in user's inventory
function get_amount_from_inventory($conn, $userId, $foodId) {
    $sql = "SELECT amount From inventory Where userId = :userId AND foodId = :foodId";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'userId' => $userId,
      'foodId' => $foodId
    ]);
    return $stmts['amount'];
}

// decreases the amount of an ingredient in user's inventory
function decrease_amount_from_inventory($conn, $userId, $foodId, $amount) {
    $sql = "UPDATE inventory
            SET amount = :amount
            WHERE userId = :userId AND foodId = :foodId";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'userId' => $id,
      'foodId' => $foodId,
      'amount' => $amount
    ]);
}



?>
