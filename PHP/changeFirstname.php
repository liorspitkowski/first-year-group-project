<?php
  $newFN = $_POST[''];
  $ID = $_POST[''];

  require "DatabaseHandler.php";

  $conn = connect();

  $sql = "UPDATE users SET firstName = :fn WHERE userId = :id";
  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ':fn' => $newFN,
    ':id' => $ID,
  ]);
  echo 'flag=1;';
?>
