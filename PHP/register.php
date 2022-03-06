<?php

  function GetID($uname)
  {
    $conn = connect();
    $sql = 'SELECT userId FROM users WHERE username = :name';
    $stmt = $conn->prepare($sql);
    $stmt->execute([':name' => $uname]);
    while($row = $stmt->fetch()) {
      echo 'flag=1;username=' . $row["userId"] . ';';
    }
  }

  $fname = $_POST['first_name'];
  $lname = $_POST['last_name'];
  $un = $_POST['user_name'];
  $pw = $_POST['user_password'];
  $email = $_POST['user_email'];

  require "DatabaseHandler.php";

  $conn = connect();

  $sql = "SELECT * FROM users WHERE username = :name";
  $stmt = $conn->prepare($sql);

  $stmt->execute([':name' => $un]);

  if ($stmt->rowCount() > 0) {
    echo 'flag=0;';
  }
  else {
    $sql = "INSERT INTO users (firstName, secondName, username, hashedPassword, hashedEmail)
    VALUES (:fn, :lan, :usn, :pass, :em)";
    $stmt = $conn->prepare($sql);

    $stmt->execute([
      ':fn' => $fname,
      ':lan' => $lname,
      ':usn' => $un,
      ':pass' => hash("sha256", $pw),
      ':em' => hash("sha256", $email),
    ]);
    GetID($un);
  }
?>
