<?php

  require "DatabaseHandler.php";

  function part1()
  {
    // $ID = $_POST[''];
    // $email = $_POST[''];

    $conn = connect();

    $sql = 'SELECT hashedEmail FROM users WHERE userId = :ID';
    $stmt = $conn->prepare($sql);

    $stmt->execute([':ID' => $ID]);

    while($row = $stmt->fetch()) {
      if ($row["hashedEmail"] == hash("sha256", $email)) {
        $code = rand(1000, 9999);
        mail($email, "verify", $code);
        echo 'flag=1;code=' . $code . ';';
      }
      else {
        // wrong email
        echo 'flag=0;';
      }
    }
  }

  function part2()
  {
    // $ID = $_POST[''];
    // $newPW = hash("sha256", $_POST['']);

    $conn = connect();

    $sql = "UPDATE users SET hashedPassword = :pw WHERE userId = :id";
    $stmt = $conn->prepare($sql);

    $stmt->execute([
      ':usn' => $newPW,
      ':id' => $ID,
    ]);
    echo 'flag=1;';

  }

  // selection to check if doinp part 1 or 2
  if ($_POST[''] == null) {
    part1();
  }
  else {
    part2();
  }
?>
