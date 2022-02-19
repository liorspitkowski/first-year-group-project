<?php
  // recieve $fname, $lname, $un, $pw
  // check user does not allready apc_exist
  // add user to table
  //return confirmation

  require "DatabaseHandler.php";

  $conn = connect();

  $sql = "SELECT * FROM user WHERE username = '" . $un . "'";
  $result = $conn->query($sql);

  // experemental sql injection protection
  // $sql = $conn->prepare('SELECT * FROM user WHERE username = :name');
  // $sql->bind_param(':name', $un);
  //
  // $sql->execute();
  // $result = $sql->get_result();

  if ($result->num_rows > 0) {
    // return user already exists
  }
  else {
    $sql = "INSERT INTO user (forename, surname, username, password)
    VALUES ('" . $fname . "','" . $lname . "','" . $un . "','" . $pw . "')";
    $conn->query($sql);

    // experemental sql injection protection
    // $sql = $conn->prepare('INSERT INTO user (forename, surname, username, password)
    // VALUES (:fn, :lan, :usn, :pass)');
    // $sql->bind_param(':fn', $fname);
    // $sql->bind_param(':lan', $lname);
    // $sql->bind_param(':usn', $un);
    // $sql->bind_param(':pass', $pw);
    //
    // $sql->execute();

    // return confirmation
  }

  $conn->close();
?>
