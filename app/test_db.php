<?php
$host = "207.246.242.176";		# host name or ip address
$user = "482239_asctool";		# database user name
$pass = "@SCT0OL58302!";		# database password
$db   = "482239_asc_database";
$con = mysqli_connect($host, $user, $pass, $db);

$result = mysqli_query($con, "SELECT * FROM cards;");

while ($node = mysqli_fetch_array($result)) {
	echo $node['product_code'] . "<br>";
}

mysqli_free_result($result);
mysqli_close($con);

// phpinfo();
