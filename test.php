<!-- test purpose only feel free to delete -->
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Test PHP</title>
  
</head>

<body>

  <!-- This is a countdown timer, set the time manully... -->
  <p id="countdown"></p>
  <script type="text/javascript">
    var distance = 9;
    var x = setInterval(function() {
      distance -= 1;
      document.getElementById("countdown").innerHTML ="Redirecting in "+ distance+"s ";
      
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("countdown").innerHTML = "Redirecting ...";
      }
    }, 1000);
  </script>

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
  // redirect back in 10s.
   header('Refresh: 0;URL= ./html/menu.html');

   
  ?>
  
</body>


</html>