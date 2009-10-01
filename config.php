<?php
$host = 'localhost:/var/mysql/mysql.sock';
$user = '3m';
$password = 'pleaseChangeMeS00n';
$dbnames = 'tt';
$dbdepts = 'ttdepartments';
@mysql_connect("$host","$user","$password") or fatal("Error connecting to server. Please contact an admin.");
mysql_query('SET NAMES utf8'); // This line lets us use "Renee"
function mysqlquery($db, $query){
  @mysql_select_db("$db") or fatal("Error opening database. Please contact an admin.");
  $sqloutput=mysql_query("$query") or fatal("Query failed. Please retry.");
  return $sqloutput;
}
function fatal($message) {
  @header("HTTP/1.1 500 Internal Server Error");
  die($message);
}
?>
