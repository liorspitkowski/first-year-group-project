<?php
  $un = $_POST['user_name'];
  $pw = $_POST['user_password'];

  require "DatabaseHandler.php";

  $conn = connect();

  $sql = 'SELECT hashedPassword, userId FROM users WHERE username = :name';
  $stmt = $conn->prepare($sql);

  $stmt->execute([':name' => $un]);

  if ($stmt->rowCount() > 0) {
    while($row = $stmt->fetch()) {
      if ($row["hashedPassword"] == hash("sha256", $pw)) {
        echo 'flag=2;username=' . $row["userId"] . ';';
      }
      else {
        // wrong password
        echo 'flag=1;';
      }
    }
  }
  else {
    // wrong username
    echo 'flag=0;';
  }
?>
