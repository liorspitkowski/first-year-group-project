<?php

require "DatabaseHandler.php";

$page_result = -1;

$name = $_GET['recipeName'];
//$name = "tortillas";

$conn = connect();

$sql = "SELECT * FROM recipes WHERE recipeName = :name";
$stmt = $conn->prepare($sql);
$stmt->execute([
  'name' => $name
]);

if ($row = $stmt->fetch()) {
  $page_result = 200;
  $id = $row['recipeId'];
  // so javaScript can access recipeId
  echo ("<label id='recipe_id' hidden>$id</label>");
  echo ("<label id='recipe_name' hidden>$name</label>");
  $instructions = $row['instructions'];
  $TTM = $row['timeToMake'];
  $foods = [];
  $portions = $row['portions'];
  $sql = "SELECT foods.*, ingredients.amount from ingredients JOIN foods ON ingredients.foodId=foods.foodId and ingredients.recipeId = $id";
  $result = $conn->query($sql);
  while ($food = $result->fetch()) {
    $foodStr = $food['amount'] . $food['defaultMeasurmentUnits'] . " " . $food['foodName'];
    array_push($foods, $foodStr);
  }
} else {
  $page_result = 404;
}

?>

<!--Written and maintained by Hanmin Liu-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>
    <?php
    echo ("Foogle - " . $name);
    ?>
  </title>
  <link rel="stylesheet" href="../css/samplerecipe.css">

  <script src="../javaScript/samplerecipe.js"></script>
  <script src="../javaScript/jquery-3.6.0.js"></script>
  <script src="../javaScript/removeFromInventory.js"></script>
  <script src="../javaScript/editShoppingList.js"></script>
  <script src="../javaScript/getCookie.js"></script>
  <script src="../javaScript/getValue.js"></script>
</head>

<body>
  <!-- header -->
  <section class="header" id="header">
    <script>
      $("#header").load("../html/header_php.html");
    </script>
  </section>

  <!-- search space begin -->
  <section class="middle-box" id="middle-box">
    <div class="column-1" id="sidebar-box">
      <script>
        $('#sidebar-box').load("../html/sidebar_php.html");
      </script>
    </div>
    <div class="column-2">
      <div class="search-panel">
        <form id="search_form">
          <div class="search">
            <div class="search-box">
              <input type="text" name='user_search' id="user_search" placeholder="Search some food..." required>
            </div>
            <div class="search-comfirm">
              <input type="submit" value="Search" id="search_button">
            </div>
          </div>
          <div class="search-selection">
            <input type="checkbox" id="filter1" name="filter1" value="vegi">
            <label for="filter1">Vegi</label>

            <input type="checkbox" id="filter2" name="filter2" value="vegan">
            <label for="filter2">Vegan</label>


            <input type="checkbox" id="inv_search" name="inv_search" value="inv_search">
            <label for="inv_search">from inventory</label>
          </div>


        </form>


        <div class="search-display">

          <?php
          if ($page_result == 200) {
            echo "<div id='result-recipe-name'>\n";
            echo "<h1>$name</h1>\n";
            echo "</div>\n";
            echo "<div id='result-recipe-info'>\n";
            echo "<h2>Portions: $portions</h2>\n";
            echo "<h2>Time To Make: $TTM</h2>\n";
            echo "</div>\n";
            echo "<div id='result-recipe-instructions'>\n";
            foreach ($foods as $ingridient) {
              echo "<p>$ingridient</p>\n";
            }
            echo "</div>";
            echo "<div id='result-recipe-instructions'>";
            echo "<p>$instructions</p>";
            echo "</div>";
          } else {
            echo "<h1>404 Not found</h1>";
            echo "<p>$result</p>";
          }
          ?>

          <form id="add_to_list" onsubmit="event.preventDefault(); addToSL();"><button type="submit">add to shopping list</button>
            portions:<input id="portions" name="portions" type="number" min="0" step="any" value=""></form><br>
          <form id="remove_from_list" onsubmit="event.preventDefault(); removeFromSL();"><button type="submit">remove from shopping list</button></form><br>
          <form id="remove_from_inventory" onsubmit="event.preventDefault(); remove_from_inventory();"><button type="submit">remove from inventory</button></form><br>

        </div>
      </div>
    </div>
  </section>
  <!-- footer -->
  <section class="footer">
    <script>
      $(".footer").load("../html/footer.html");
    </script>
  </section>
</body>