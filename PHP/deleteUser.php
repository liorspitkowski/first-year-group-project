<?php

  $ID = $_POST[''];

  if ($ID == 0) {
    //can't delete admin
    echo 'flag=0;';
  }
  else {
    require "DatabaseHandler.php";

    $conn = connect();
    $sql = "DELETE FROM users WHERE userId = :id";
    $stmt = $conn->prepare($sql);

    $stmt->execute([
      ':id' => $ID,
    ]);
    echo 'flag=1;';
  }
?>
