<?php

function get_connection()
{
	$host = "207.246.242.176";		# host name or ip address
	$user = "482239_asctool";		# database user name
	$pass = "@SCT0OL58302!";		# database password
	$database = "482239_asc_database";

	# get connection with mysql
	$con = mysqli_connect($host, $user, $pass, $database);
	// Check connection
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	return $con;
}

function close_connection($con)
{
	mysqli_close($con);
}

function make_query($query, $con)
{

	$result = mysqli_query($con, $query);
	if (!$result) {
		$message  = 'Invalid query: ' . mysqli_error() . "\n";
		$message .= 'Whole query: ' . $query;
		die($message);
	}

	return $result;
}

function free_query($result)
{
	mysqli_free_result($result);
}
