<?php include 'config.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>The Tech's Mast and Mailing List Maintenance System</title>
</head>
<body>
<table>
  <tr>
    <th> Dates </th>
    <th> Department </th>
    <th> Position </th>
    <th> Status </th>
  </tr>
<?php
$email = $_GET["email"];
$query="SELECT * FROM `bio` WHERE `email`='$email' ORDER BY `edate`, `active` DESC, `mail` DESC";
$result=mysqlquery($dbnames,$query);
$first=mysql_result($result,0,"first");
$middle=mysql_result($result,0,"middle");
$last=mysql_result($result,0,"last");
if ($middle==""){
	$name="$first $last";
}
else {
	$name="$first $middle $last";
}
echo "<H1 align=\"center\">Staff History Report for $name</h1>";
$email=mysql_result($result,0,"email");
$num=mysql_numrows($result);

/*$currentjob="";
$oldjob="";
$currentmail="";
$oldmail="";
*/
for ($i=0; $i < $num; $i++) {
	$bdate=mysql_result($result,$i,"bdate");
	$edate=mysql_result($result,$i,"edate");
	$mail=mysql_result($result,$i,"mail");
	$active=mysql_result($result,$i,"active");
	$dept=mysql_result($result,$i,"dept");
	$position=mysql_result($result,$i,"position");
	$startdate=date('j F Y', mktime(0, 0, 0, substr($bdate,5,2), substr($bdate,8,2), substr($bdate,0,4)));
	if (substr($edate, 0, 4)==0)
		$stopdate="Present";
	else
		$stopdate=date('j F Y', mktime(0, 0, 0, substr($edate,5,2), substr($edate,8,2), substr($edate,0,4)));
	if ($active==1){
		$status="<b>Current Position</b>";
	}
	else if ((substr($edate, 0, 4)!=0)&&($mail==1)){
		$status="Previously Held Position";
	}
	else if ((substr($edate, 0, 4)==0)&&($mail==1)){
		$status="Mailing List Member";
	}
	else{
		$status="";
	}
/*	if ($active==1){
		$currentjob="$dept - $position";
	}
	if ($mail==1){
		$currentmail="$currentmail<p>$dept - $position - $date</p>";
	}
	if ($active==1){
		$currentjob="$currentjob<p>$dept - $position</p>";
	}
	if ($active==1){
		$currentjob="$currentjob<p>$dept - $position</p>";
	}
}
if ($currentjob){
	echo "<H2>Currently Held Position:</H2>";
	echo $currentjob;
}
if (!$currentjob){
	echo "<H2>No Position Currently Held</H2>";
}
if ($currentmail){
	echo "<H2>Currently Receiving Mail From:</H2>";
	echo $currentmail;
}
if (!$currentmail){
	echo "<H2>Not Currently Receiving Any Mail</H2>";
}
*/
echo "<tr> <td>
$startdate - $stopdate
</td>
<td>
$dept
</td>
<td>
$position
</td>
<td>
$status
</td>
</tr>";
}
?>
</table>
</body>
</html>
