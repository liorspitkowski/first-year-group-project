<?php
  $newLN = $_POST[''];
  $ID = $_POST[''];

  require "DatabaseHandler.php";

  $conn = connect();

  $sql = "UPDATE users SET secondName = :lan WHERE userId = :id";
  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ':lan' => $newLN,
    ':id' => $ID,
  ]);
  echo 'flag=1;';
?>
