<?php

  require "DatabaseHandler.php";
  require "MailManager.php";

  function part1()
  {
    $un = $_POST['username'];
    $email = $_POST['email'];

    $conn = connect();

    $sql = 'SELECT hashedEmail FROM users WHERE username = :UN';
    $stmt = $conn->prepare($sql);

    $stmt->execute([':UN' => $un]);

    while($row = $stmt->fetch()) {
      if ($row["hashedEmail"] == hash("sha256", $email)) {
        $code = rand(1000, 9999);
        $mail = new MailManager(
        "dbhost.cs.man.ac.uk", "y66466tl", "SpagetiC0de", "2021_comp10120_x18");
        $mail->set_subject("verify");
        $mail->add_recipient($email);
        $mail->set_body($code);
        try {
          $mail->send();
          echo 'flag=2;code=' . $code . ';';
        }
        catch (Exception $e) {
          echo 'flag=1;error=' . $e->getMessage() . ';';
        }
      }
      else {
        // wrong email
        echo 'flag=0;';
      }
    }
  }

  function part2()
  {
    // $un = $_POST[''];
    // $newPW = hash("sha256", $_POST['']);

    $conn = connect();

    $sql = "UPDATE users SET hashedPassword = :pw WHERE username = :UN";
    $stmt = $conn->prepare($sql);

    $stmt->execute([
      ':usn' => $newPW,
      ':UN' => $un,
    ]);
    echo 'flag=1;';

  }

  part1();

  // selection to check if doing part 1 or 2
  // if ($_POST[''] == null) {
  //   part1();
  // }
  // else {
  //   part2();
  // }
?>
