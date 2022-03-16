<?php

require "DatabaseHandler.php";

function get_profile() {
    // retrieves variables from POST
    $id = $_POST["user_id"];

    $conn = connect(True); // connects to database

    $results = get_user_details($conn, $id);
    if ($results != null) {
        $username = $results['username'];
        $firstName = $results['firstName'];
        $secondName = $results['secondName'];
        $recipeList = get_user_recipes($conn, $id);
        $response = $username . '#' . $firstName . '#' . $secondName;
        if ($recipeList == null) {
            // no user-made recipes
            echo($response);
        } else {
            // add user-made recipes
            for ($i = 0; $i < sizeof($recipeList); $i++) {
                $response = $response . '#' . $recipeList[$i];
            }
            echo($response);
        }
    } else {
        //user doesn't exist
        echo("flag=0");
    }
}

// gets user's username, first name and second name from user id
function get_user_details($conn, $id) {
    $sql = "SELECT username, firstName, secondName FROM users WHERE userId = :id";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'id' => $id
    ]);
    $results = $stmt->fetch();
    return $results;
}

// gets all the recipes created by the user and returns them in an array
function get_user_recipes($conn, $id) {
    $sql = "SELECT recipeName FROM recipes WHERE userId = :id";
    $stmt = $conn->prepare($sql);
    $stmt ->execute([
      'id' => $id
    ]);

    $recipeList = [];

    if ($stmt != null) {
        while($row = $stmt->fetch()) {
            array_push($recipeList, $row['recipeName']);
        }
    }

    return $recipeList;

}

get_profile();

?>
