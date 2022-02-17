<?php
  // receive $un, $pw
  // search database for hashed passwords where usernames == $un and check if it matches
  // if a match return confirmation
  // SQL = "SELECT hashed_password FROM users WHERE username = " . $pw;

  require "DatabaseHandler.php";

  $conn = connect();
  $sql = "SELECT password FROM user WHERE username = '" . $pw; . "'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      if ($row["hashed_password"] == $pw) {
        // return confirmation
      }
      else {
        // incorect password
      }
    }
  }
  else {
    // user not found
  }

  $conn->close();
?>
