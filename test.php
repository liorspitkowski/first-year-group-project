<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Test Project</title>

	<?php


	echo 'Hello ' . htmlspecialchars($_POST["user_name"]) . '!' . '\nYour password is: ' . htmlspecialchars($_POST["user_password"]);


	?>


</head>

<body>

</body>

</html>