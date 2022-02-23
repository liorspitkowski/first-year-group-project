<?php

$host = "dbhost.cs.man.ac.uk";
$dbname = "2021_comp10120_x18";
$user = "f18903ls";
$pass = "f18903lsf18903ls";
$conn;

//creates connection object to database
function connect(bool $debug = false){

  global $host, $dbname, $conn, $user, $pass;

  try
  {
    $conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
    echo "Connected to $host successfully. \n";
    if ($debug){
      $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    }
    return $conn;
  }
  catch (PDOException $pe)
  {
    die("Could not connect to $host :" . $pe->getMessage());
  }
}

//creates connection using specified username and pass for debuging
function adminConnect(String $user, String $pass){

  global $host, $dbname, $conn;

  try
  {
    $conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    echo "Connected to $host successfully. \n";
    return $conn;
  }
  catch (PDOException $pe)
  {
    die("Could not connect to $host :" . $pe->getMessage());
  }
}

function SQLquery($sql){

  global $conn;

  try{
    $conn->query($sql);
    echo "SQL query ran successfully \n";
  }
  catch (PDOException $pe)
  {
    echo "ERROR : SQLquery '$sql' failed to run \n";
  }

}

/*
require "DatabaseHandler.php";
//creates connection object with debug mode on
$conn = connect(true);

//creates connection object with debug mode off
$conn = connect(); // or $conn = connect(false);

$sql = "CREATE table";

//unsecure for user input only use for sql with not user input
$conn->query($sql);

//secure way of passing user inputed variable into sql pre-formated statments
$pre_formated_sql = "SELECT FROM table (column1, column2) VALUES (:value1, :value2)"
$stmt = $conn->prepare($sql);
$stmt->execute([
  'valu1' => $variable1,
  'value2' => $variable2
]);
*/

 ?>
