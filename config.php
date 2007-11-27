<?php
$host = 'localhost:/var/mysql/mysql.sock';
$user = '3m';
$password = 'pleaseChangeMeS00n';
$dbnames = 'tt';
$dbdepts = 'ttdepartments';
function mysqlquery($db, $query){
	global $host, $user, $password, $dbnames, $dbdepts;
	mysql_connect("$host","$user","$password") or die("Error connecting to server. Please try again later.");
	mysql_select_db("$db") or die("Error opening database. Please contact an admin.");
	$sqloutput=mysql_query("$query") or die("Query failed. Please retry.");
	mysql_close();
	return $sqloutput;
}
?> 
