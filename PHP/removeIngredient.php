<?php

require "DatabaseHandler.php";

// removes all ingredients of a recipe from a user's inventory
function remove_ingredients() {
    // retrieves variables from POST
    $id = $_POST["id"];
    $recipeName = $_POST["recipeName"];
    $amount = $_POST["amount"];

    $conn = connect(True); // connects to database

    $foodId = get_foodId($conn, $ingredient);

    $current_quantity = get_quantity($conn, $id, $foodId);

    if ($current_quantity > 0) {
        // already have ingredient
        if ($current_quantity == $amount) {
            // delete record
            $sql = "DELETE FROM inventory WHERE userId = :userId AND foodId = ;foodId";
            $stmt = $conn->prepare($sql);
            $stmt ->execute([
              'userId' => $id,
              'foodId' => $foodId
            ]);
        } else {
          // decrease amount
          $amount = $amount - $current_quantity;
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
    }
}

// fetches foodId from name of ingredient
function get_foodId($conn, $ingredient) {
    $sql = "SELECT foodId FROM foods WHERE foodName = :ingredient";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'ingredient' => $ingredient
    ]);
    $results = $stmt->fetch();
    return $results;
}

// fetches quantity of ingredient from database
function get_quantity($conn, $id, $foodId) {
  $sql = "SELECT amount FROM inventory WHERE userId = :id AND foodId = :foodId";
  $stmt = $conn->prepare($sql);
  $stmt ->execute([
    'id' => $id,
    'foodId' => $foodId
  ]);
  $results = $stmt->fetch();
  return $results;
}

remove_ingredients();

?>
