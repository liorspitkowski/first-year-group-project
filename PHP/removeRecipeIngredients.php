<?php

// written and maintained by Lior Spitkowski

require "DatabaseHandler.php";

// removes all ingredients of a recipe from a user's inventory
function remove_recipe_ingredients() {
    // retrieves variables from POST
    $id = $_POST["user_id"];
    $recipeId = $_POST["recipe_id"];

    // can be changed back to work with recipe name
    // $recipeName = $_POST["recipeName"];
    // $recipeId = get_recipeId($conn, $recipeName);

    $conn = connect(True); // connects to database



    $ingredientsAndQuantities = get_ingredients_and_quantities_recipe($conn, $recipeId);
    $recipeIngredientsList = $ingredientsAndQuantities[0];
    $recipeAmountsList = $ingredientsAndQuantities[1];

    $ingredientCount = sizeof($recipeIngredientsList);
    $confirmationCounter = 0; // used to ensure all ingredients are successfully updated in the databse

    // for every ingredient in the recipe, get the amount in the user's inventory and decrease the amount of the ingredient in the users' inventory, if theres not enough, set to 0
    for ($i = 0; $i < $ingredientCount; $i++) {
        $amountInInventory = get_amount_from_inventory($conn, $id, $recipeIngredientsList[$i]);
        if ($amountInInventory != null) {
            // if ingredient is in te user's inventory
            $amountInInventory = $amountInInventory['amount'];
            if ($amountInInventory > $recipeAmountsList[$i]) {
                $updatedAmount = $amountInInventory - $recipeAmountsList[$i];
                decrease_amount_from_inventory($conn, $id, $recipeIngredientsList[$i], $updatedAmount);
                $deleted = False;
            } else {
                $updatedAmount = 0;
                delete_amount_from_inventory($conn, $id, $recipeIngredientsList[$i]);
                $deleted = True;
            }
            $confirmationCounter = confirmation($conn, $confirmationCounter, $deleted, $id, $recipeIngredientsList[$i], $updatedAmount);
        } else {
            // if the ingredient is not in the user's inventory, no need to check
            ++$confirmationCounter;
        }
    }

    // sends flag as 1 if all ingredients were update correctly or 0 it not
    if ($confirmationCounter == $ingredientCount) {
        echo("flag=1");
    } else {
        echo("flag=0");
    }
}

// gets recipeId of recipe from database
function get_recipeId($conn, $recipeName) {
    $sql = "SELECT recipeId From recipes Where recipeName = :recipeName";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'recipeName' => $recipeName
    ]);
    $results = $stmt->fetch();
    return $results['recipeId'];
}

// get a list of the ingredients and their quanitites in the recipe
function get_ingredients_and_quantities_recipe($conn, $recipeId) {
    $sql = "SELECT foodId, amount From ingredients Where recipeId = :recipeId";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'recipeId' => $recipeId
    ]);

    $recipeIngredientsList = [];
    $recipeAmountsList = [];

    if ($stmt != null) {
        while($row = $stmt->fetch()) {
            array_push($recipeIngredientsList, intval($row['foodId']));
            array_push($recipeAmountsList, intval($row['amount']));
        }
    }
    return array($recipeIngredientsList, $recipeAmountsList);
}

// fetches amount of ingredient in user's inventory
function get_amount_from_inventory($conn, $userId, $foodId) {
    $sql = "SELECT amount From inventory Where userId = :userId AND foodId = :foodId";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'userId' => $userId,
      'foodId' => $foodId
    ]);
    $results = $stmt->fetch();
    return $results;
}

// decreases the amount of an ingredient in user's inventory
function decrease_amount_from_inventory($conn, $id, $foodId, $amount) {
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

function delete_amount_from_inventory($conn, $id, $foodId) {
  $sql = "DELETE FROM inventory WHERE userId = :userId AND foodId = :foodId";
  $stmt = $conn->prepare($sql);
  $stmt ->execute([
    'userId' => $id,
    'foodId' => $foodId
  ]);
}

// increments confirmationCounter if the correct update was completed (if the new amount is updated/deleted as expected)
function confirmation($conn, $confirmationCounter, $deleted, $id, $foodId, $amountExpected) {
    $sql = "SELECT amount FROM inventory WHERE userId = :id AND foodId = :foodId";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'id' => $id,
      'foodId' => $foodId
    ]);
    $results = $stmt->fetch();
    if ($deleted) {
        if ($results == null) {
            ++$confirmationCounter;
        }
    } else {
        $results = $results[0];
        if ($results == $amountExpected) {
            ++$confirmationCounter;
        }
    }
    return $confirmationCounter;
}

remove_recipe_ingredients();

?>
