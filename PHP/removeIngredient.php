<?php

require "DatabaseHandler.php";

// adds ingredient with quantity to user's inventory (checking if they already had it in which case it adds to the quantity)
function remove_ingredient() {
    // retrieves variables from POST
    $id = $_POST["user_id"];
    $ingredient = $_POST["ingredient"];
    $quantity = $_POST["quantity"];

    $conn = connect(True); // connects to database

    $foodId = get_foodId($conn, $ingredient)[0];
    $current_quantity = get_quantity($conn, $id, $foodId);

    if ($current_quantity > 0) {
        // already have ingredient, update it
        if ($current_quantity[0] <= $quantity) {
            // if the current current quantity is less than or equal to amount to be deleted, delete record
            $sql = "DELETE FROM inventory WHERE userId = :userId AND foodId = :foodId";
            $stmt = $conn->prepare($sql);
            $stmt ->execute([
              'userId' => $id,
              'foodId' => $foodId
            ]);
        } else {
            // if current quanitity is more than amount to be deleted, decrease by amount
            $quantity = $current_quantity[0] - $quantity;
            $sql = "UPDATE inventory
                    SET amount = :amount
                    WHERE userId = :userId AND foodId = :foodId";
                    $stmt = $conn->prepare($sql);
            $stmt->execute([
              'userId' => $id,
              'foodId' => $foodId,
              'amount' => $quantity
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

remove_ingredient();

?>
