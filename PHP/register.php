<?php
  $fname = $_POST['first_name'];
  $lname = $_POST['last_name'];
  $un = $_POST['user_name'];
  $pw = $_POST['user_password'];

  require "DatabaseHandler.php";

  $conn = connect();

  $sql = "SELECT * FROM users WHERE username = :name";
  $stmt = $conn->prepare($sql);

  $stmt->execute([':name' => $un]);

  if ($stmt->rowCount() > 0) {
    echo 0;
  }
  else {
    $sql = "INSERT INTO users (firstName, secondName, username, hashedPassword)
    VALUES (:fn, :lan, :usn, :pass)";
    $stmt = $conn->prepare($sql);

    $stmt->execute([
      ':fn' => $fname,
      ':lan' => $lname,
      ':usn' => $un,
      ':pass' => hash("sha256", $pw),
    ]);
    echo 1;
  }
?>
