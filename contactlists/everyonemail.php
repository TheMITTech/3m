<?php
include '../config.php';
//$query="SELECT DISTINCT email FROM bio WHERE mail=1 ORDER BY email";
$query="SELECT DISTINCT email FROM bio ORDER BY email";
$result=mysqlquery($dbnames,$query);
$num=mysql_numrows($result);
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Everyone Mailing List</title>
</head>

<body>
<table align="center">';
for ($i=0; $i < $num; $i++) {
	$email=mysql_result($result,$i,"email");
	echo "<tr><td>$email</td></tr>";
}
echo '</table>
</body>
</html>';
?>
