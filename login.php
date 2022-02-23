<?php
  // $un = $_POST['user_name'];
  // $pw = $_POST['user_password'];

  $un = 'TheBigZig';
  $pw = 'hashed';

  require "DatabaseHandler.php";

  $conn = connect();

  $sql = 'SELECT hashedPassword, userId FROM users WHERE username = :name';
  $stmt = $conn->prepare($sql);

  $stmt->execute([':name' => $un]);

  if ($stmt->rowCount() > 0) {
    while($row = $stmt->fetch()) {
      if ($row["hashedPassword"] == $pw) {
        echo 2;
        echo $row["userId"];
      }
      else {
        // wrong password
        echo 1;
      }
    }
  }
  else {
    // wrong username
    echo 0;
  }
?>
