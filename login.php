<?php
  // receive $un, $pw
  // search database for hashed passwords where usernames == $un and check if it matches
  // if a match return confirmation
  // SQL = "SELECT hashed_password FROM users WHERE username = " . $pw;

  // $un = "ziggy112";
  // $pw = "12345";

  require "DatabaseHandler.php";

  $conn = connect();

  $sql = "SELECT password FROM user WHERE username = '" . $un . "'";
  $result = $conn->query($sql);

  // experemental sql injection protection
  // $sql = $conn->prepare('SELECT password FROM user WHERE username = :name');
  // $sql->bind_param(':name', $un);
  //
  // $sql->execute();
  // $result = $sql->get_result();

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      if ($row["hashed_password"] == $pw) {
        echo "correct";
      }
      else {
        echo "incorrect password";
      }
    }
  }
  else {
    echo "incorrect username";
  }

  $conn->close();
?>
