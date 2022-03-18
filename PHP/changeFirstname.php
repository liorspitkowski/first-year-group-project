<?php
  $newFN = $_POST['user_changed_fname'];
  $ID = $_POST['user_id'];

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
