<?php
  // recieve $fname, $lname, $un, $pw
  // check user does not allready apc_exist
  // add user to table
  //return confirmation

  require "DatabaseHandler.php";

  $conn = connect();
  $sql = "SELECT * FROM user WHERE username = '" . $un . "'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // return user already exists
  }
  else {
    $sql = "INSERT INTO user (forename, surname, username, password)
    VALUES ('" . $fname . "','" . $lname . "','" . $un . "','" . $pw . "')";
    $conn->query($sql);
    // return confirmation
  }

  $conn->close();
?>
