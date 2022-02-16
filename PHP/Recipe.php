<?php

require "DatabaseHandler.php";

connect();
$sql = "INSERT INTO Recipies (recipeName, numIngredients) VALUES ('beans on toast', 8)";
SQLquery($sql);

function addRecipie(){

}

 ?>