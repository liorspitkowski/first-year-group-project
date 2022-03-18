<?php

  $ID = $_POST['user_id'];

  if ($ID == 0 || $ID == 1) {
    //can't delete admin/guest
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
