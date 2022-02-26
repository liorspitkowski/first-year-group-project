<?php
  $un = $_POST['user_name'];
  $pw = $_POST['user_password'];

  require "DatabaseHandler.php";

  $conn = connect();

  // $sql = "SELECT password FROM user WHERE username = '" . $un . "'";
  // $result = $conn->query($sql);

  // experemental sql injection protection
    

  $sql = $conn->prepare('SELECT hashedPassword, userId FROM users WHERE username = :name');
  $sql->bindParam(':name', $un);

  $sql->execute();
  $sql->setFetchMode(PDO::FETCH_ASSOC);

  if ($sql != null) {
    while($row = $sql->fetch()) {
      if ($row["hashedPassword"] == $pw) {
        echo 2;
        echo $row["userId"];
      }
      else {
        // wrong password
        echo 1;
      }
    }
  }
  else {
    // wrong username
    echo 0;
    
  }
  

?>
