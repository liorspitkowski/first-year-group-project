<?php

require "DatabaseHandler.php";

// adds ingredient with quantity to user's inventory (checking if they already had it in which case it adds to the quantity)
function add_ingredient() {
    // retrieves variables from POST
    $id = $_POST["id"];
    $ingredient = $_POST["ingredient"];
    $quantity = $_POST["quantity"];
    $unit = $_POST["unit"];

    $conn = connect(True); // connects to database

    $foodId = get_foodId($conn, $ingredient)[0];
    $quantity = unit_conversion($quantity, $unit);
    $current_quantity = get_quantity($conn, $id, $foodId);

    if ($current_quantity > 0) {
        // already have ingredient, update it
        $quantity = $current_quantity[0] + $quantity;
        $sql = "UPDATE inventory
                SET amount = :amount
                WHERE userId = :userId AND foodId = :foodId";
    } else {
        // didn't previously have ingredient, adds record
        $sql = "INSERT INTO inventory (userId, foodId, amount)
                VALUES (:userId, :foodId, :amount)";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute([
      'userId' => $id,
      'foodId' => $foodId,
      'amount' => $quantity
    ]);
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
    return $quantity;
}

add_ingredient();

?>
