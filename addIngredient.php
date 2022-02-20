<?php

require "DatabaseHandler.php";

// adds ingredient with quantity to user's inventory (checking if they already had it in which case it adds to the quantity) in for (String, String, double, String)
function add_ingredient($id, $ingredient, $quantity, $unit) {
    $conn = connect(True);

    $foodId = get_foodId($conn, $ingredient)->fetch()['foodId'];
    $quantity = unit_conversion($quantity, $unit);
    $current_quantity = get_quantity($conn, $id, $foodId);

    if (count($result) > 0) {
        // already have ingredient
        $quantity = $current_quantity + $quantity;
        $sql = "UPDATE inventory
                SET quantity = :quantity
                WHERE user_id, food_id = :id, ;foodId"
    } else {
        // didn't previously have ingredient
        $sql = "INSERT INTO inventory (id, foodId, quantity)
                VALUES (:id, :foodId, :quantity)";
    }

    $stmt-> $conn->prepare($sql);
    $stmt->execute([
      'id' => $id,
      'foodId' => $foodId,
      'quantity' => $quantity
    ]);

    $conn ->close();
}

// fetches foodId from name of ingredient
// credit for function -> Thomas
function get_foodId($conn, $ingredient) {

    $sql = "SELECT foodId FROM foods WHERE foodName = :ingredient";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'ingredient' => $add_ingredient
    ]);
    return $stmts;
}

// fetches quantity of ingredient from database
function get_quantity($conn, $id, $foodId) {
  $sql = "SELECT quantity FROM inventory WHERE user_id, food_id = :id, :foodId";
  $stmt = $conn->prepare($sql);
  $stmt ->execute([
    'id' => $id,
    'foodId' => $foodId
  ]);
  return $stmts;
}

// convert units to standard ones in database
function unit_conversion($quantity, $unit) {
    switch ($unit) {
      case "lb":
          // convert lb to kg
          $quantity = $quantity * 0.45;
      case "gallon":
          // convert gallons to litres
          $quantity = $quantity * 3.79;
    }
    return $quantity
}

?>
