<?php

require "DatabaseHandler.php";

// adds ingredient with quantity to user's inventory (checking if they already had it in which case it adds to the quantity)
function add_ingredient() {
    // //retrieves variables from POST
    $id = $_POST["user_id"];
    $ingredient = $_POST["ingredient"];
    $quantity = $_POST["quantity"];

    $conn = connect(True); // connects to database

    $foodId = get_foodId($conn, $ingredient)[0];
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

    // confirmation check
    $confirmationSignal = confirmation($conn, $id, $foodId, $quantity);
    echo($confirmationSignal);
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

function confirmation($conn, $id, $foodId, $amountExpected) {
    $sql = "SELECT amount FROM inventory WHERE userId = :id AND foodId = :foodId";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'id' => $id,
      'foodId' => $foodId
    ]);
    $results = $stmt->fetch()[0];
    if ($results == $amountExpected) {
        return 1;
    } else {
        return 0;
    }
}

add_ingredient();

?>
