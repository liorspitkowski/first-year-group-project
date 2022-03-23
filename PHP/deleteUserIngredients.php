<?php

// written and maintained by Lior Spitkowski

require "DatabaseHandler.php";

// deletes inventory of user and reassigns recipes to admin user and returns confirmation
function delete_and_confirm() {
    $id = $_POST["user_id"]; // retrieves variables from POST

    $conn = connect(True); // connects to database

    delete_user_ingredients($conn, $id);
    reassign_recipes($conn, $id);
    delete_user_ingredients($conn, $id);

    if (ingredients_confirmation($conn, $id) && recipes_confirmation($conn, $id) && shoppingList_confirmation($conn, $id)) {
        echo("flag=1");
    } else {
        echo("flag=0");
    }
}

// deletes ingredients in inventory associate with user
function delete_user_ingredients($conn, $id) {
    $sql = "DELETE FROM inventory WHERE userId = :userId";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'userId' => $id
    ]);
}

// ressagins recipes with the user id to the admin
function reassign_recipes($conn, $id) {
    $sql = "UPDATE recipes SET userId = 0 WHERE userId = :userId";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'userId' => $id
    ]);
}

// deletes user shopping list
function delete_user_ingredients($conn, $id) {
    $sql = "DELETE FROM shopRecipes WHERE userId = :userId";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'userId' => $id
    ]);
}

// ensures there are no ingredients in the deleted user's inventory
function ingredients_confirmation($conn, $id) {
    $sql = "SELECT userId FROM inventory WHERE userId = :id";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'id' => $id
    ]);
    $results = $stmt->fetch();
    if ($results == null) {
        $message = True;
    } else {
        $message = False;
    }
    return $message;
}

// ensures there are no recipes made by the deleted user
function recipes_confirmation($conn, $id) {
    $sql = "SELECT userId FROM recipes WHERE userId = :id";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'id' => $id
    ]);
    $results = $stmt->fetch();
    if ($results == null) {
        $message = True;
    } else {
        $message = False;
    }
    return $message;
}

// ensures there are no recipes made by the deleted user
function shoppingList_confirmation($conn, $id) {
    $sql = "SELECT userId FROM shopRecipes WHERE userId = :id";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'id' => $id
    ]);
    $results = $stmt->fetch();
    if ($results == null) {
        $message = True;
    } else {
        $message = False;
    }
    return $message;
}

delete_and_confirm();

?>
