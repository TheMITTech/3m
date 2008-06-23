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
$first=mysql_result($result,$i,"first");
$middle=mysql_result($result,$i,"middle");
$last=mysql_result($result,$i,"last");
$test1=substr($position,0,9);
$test2=($test1!="Associate");
if ((substr($position,0,9)!="Associate")&&(substr($position,0,6)!="Senior")&&((substr($position,-7,7)=="Manager")||(substr($position,-6,6)=="Editor")||($position=="Editor in Chief")||($position=="Chairman")||($position=="Director"))){
echo "<tr><td>$first $middle $last</td></tr>";
}
}
?>
</table>
</body>
</html>
