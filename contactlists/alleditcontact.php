<?php
include '../config.php';
?>
<html>
<head><title>The Tech Editor Contact List</title></head>
<body>
<H1 align="center">The Tech</H1>
<H2 align="center">Editor Contact List</H2>
<table align="center"><th>Name</th><th>Department</th><th>Position</th><th>E-mail</th><th>Phone</th><th>AIM</th>
<?php
$query="SELECT * FROM bio WHERE active=1 ORDER BY dept, position, last";
$result=mysqlquery($dbnames,$query);
$num=mysql_numrows($result);
for ($i=0; $i < $num; $i++) {
$first=mysql_result($result,$i,"first");
$middle=mysql_result($result,$i,"middle");
$last=mysql_result($result,$i,"last");
$dept=mysql_result($result,$i,"dept");
$position=mysql_result($result,$i,"position");
$email=mysql_result($result,$i,"email");
$phone=mysql_result($result,$i,"phone");
$aim=mysql_result($result,$i,"aim");
if ((substr($position,-7,7)=="Manager")||(substr($position,-6,6)=="Editor")||($position=="Editor in Chief")||($position=="Chairman")||($position=="Director")||($dept=="Editors at Large")){
echo "<tr> <td>$first $middle $last</td><td>$dept</td><td>$position</td><td><a href=mailto:$email>$email</a></td><td>$phone</td><td>$aim</td></tr>";
}
}
?>
</table>
</body></html>
