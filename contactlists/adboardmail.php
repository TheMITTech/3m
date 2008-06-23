<?php
include '../config.php';
?>
<html>
<body>
<table>
<?php
$query="SELECT * FROM bio WHERE active=1 ORDER BY email";
$result=mysqlquery($dbnames,$query);
$num=mysql_numrows($result);
for ($i=0; $i < $num; $i++) {
$dept=mysql_result($result,$i,"dept");
$position=mysql_result($result,$i,"position");
$email=mysql_result($result,$i,"email");
$test1=substr($position,0,9);
$test2=($test1!="Associate");
if ($dept=="Advisory Board"){
echo "<tr><td>$email</td></tr>";
}
}
?>
</table>
</body>
</html>
