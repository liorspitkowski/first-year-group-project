<?php
  $newLN = $_POST['user_changed_lname'];
  $ID = $_POST['user_id'];

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
