<?php
$url = $_POST["url"];
$title = $_POST["title"];
$name = $_POST ["name"];
$timestamp =date('l jS \of F Y h:i:s A');
$text = "<a href='{$url}'>{$title}</a> Submitted By: ".strtoupper($name)." on: {$timestamp} <br><br> \n";
$file = fopen("./html/enterinventory.html","a+ \n");
 fwrite($file, $text);
 fclose($file);
 header('Refresh: 0;URL= ./html/enterinventory.html');
?>