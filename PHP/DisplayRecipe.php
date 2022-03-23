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

if($row = $stmt->fetch()){
  $page_result = 200;
  $id = $row['recipeId'];
  $instructions = $row['instructions'];
  $TTM = $row['timeToMake'];
  $foods = [];
  $portions = $row['portions'];
  $sql = "SELECT foods.*, ingredients.amount from ingredients JOIN foods ON ingredients.foodId=foods.foodId and ingredients.recipeId = $id";
  $result = $conn->query($sql);
  while($food = $result->fetch()){
    $foodStr = $food['amount'] . $food['defaultMeasurmentUnits'] . " " . $food['foodName'];
    array_push($foods, $foodStr);
  }

}
else{
  $page_result = 404;
}

 ?>

<!--Written and maintained by Hanmin Liu-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>myprofile</title>
    <link rel="stylesheet" href="../css/samplerecipe.css">
    <script src="../javaScript/samplerecipe.js"></script>
    <script src="../javaScript/jquery-3.6.0.js"></script>
    <script src="../javaScript/editShoppingList.js"></script>
    <script src="../javaScript/getCookie.js"></script>
    <script src="../javaScript/getValue.js"></script>
</head>

<body>
    <!-- Main contect space -->
    <section class="left-box">
        <!-- blank space on top begin -->
        <div class="topbox">
            <div class="title-box">
            Foogle
            </div>

        </div>
        <!-- blank space on top end -->

        <!-- search space begin -->
        <div class="row">
            <div class="column-1">
                <div class="sidebar">
                    <div class="sidebar-title">
                         Sidebar
                    </div>
                    <ul>
                        <li><a href="userprofile.html">My profile</a></li>
                        <li><a href="menu.html">Menu</a></li>
                        <li><a href="search.html">Search</a></li>
                        <li><a href="samplerecipe.html">Samplerecipe</a></li>
                        <li><a href="newRecipe.html">Newrecipe</a></li>
                        <li><a href="shoppinglist.html">Shopping list</a></li>
                    </ul>

                </div>
            </div>
            <div class="column-2"><div class="search-panel">
                <div class="search-bar">
                    new search button?
                </div>
                <div class="recipe-result">

                <?php
                  if ($page_result == 200){
                    echo"<div id='result-recipe-name'>\n";
                      echo"<h1>$name</h1>\n";
                    echo"</div>\n";
                    echo"<div id='result-recipe-info'>\n";
                      echo"<h2>Portions: $portions</h2>\n";
                      echo"<h2>Time To Make: $TTM</h2>\n";
                    echo"</div>\n";
                    echo"<div id='result-recipe-instructions'>\n";
                      foreach ($foods as $ingridient){
                        echo "<p>$ingridient</p>\n";
                      }
                    echo"</div>";
                    echo"<div id='result-recipe-instructions'>";
                      echo"<p>$instructions</p>";
                    echo"</div>";
                  }
                  else {
                    echo "<h1>404 Not found</h1>";
                    echo "<p>$result</p>";
                  }
                ?>

                <form id="add_to_list" onsubmit="event.preventDefault(); addToSL();"><button type="submit">add to shopping list</button>
                portions:<input id="portions" name="portions" type="number" min="0" step="any" value=""></form><br>
                <form id="remove_from_list" onsubmit="event.preventDefault(); removeFromSL();"><button type="submit">remove from shopping list</button></form><br>
                <button onclick="remove_from_inventory()">remove from inventory</button>

                </div>
            </div>
        </div>
        <!-- search space end -->

        <!-- footer begin -->
        <div class="bottom-box">
            Perhaps some 'contact us' stuff?
        </div>
        <!-- footer end -->
    </section>
    <!-- The background image section -->
    <section class="right-box">
    </section>
</body>
