<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input field
  $name = $_POST['user_name'];
  $password = $_POST['user_password'];
  if (empty($name)) {
    echo "Name is empty";
  } else {
    echo 'Entered name is :' . $name . '<br>password is :' . $password;
  }
}
?>