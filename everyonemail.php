<?php
include 'config.php';
//$query="SELECT DISTINCT email FROM bio WHERE mail=1 ORDER BY email";
$query="SELECT DISTINCT email FROM bio WHERE active=1 ORDER BY email";
$result=mysqlquery($dbnames,$query);
$num=mysql_numrows($result);
header('Content-Type: text/plain');
for ($i=0; $i < $num; $i++) {
	$email=mysql_result($result,$i,"email");
	echo "$email\n";
}
