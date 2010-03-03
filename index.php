<?php include 'config.php'; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>The Tech's Mast and Mailing List Maintenance System</title>
</head>
<body>
<H1 align="center">Mast and Mailing List Maintenance System</h1>
<table align="center">
  <tr>
    <td colspan="4"><a href="dynamicmarkmast.php">Download Tagged Masthead</a></td>
  </tr>
  <tr>
    <td colspan="4"><a href="dynamichtmlmast.php">HTML Masthead</a></td>
  </tr>
  <tr>
    <td colspan="4"><a href="manboardcontact.php">Manboard Contact List</a></td>
  </tr>
  <tr>
    <td colspan="4"><a href="everyonemail.php">Everyone's Email List</a></td>
  </tr>
  <tr>
    <td colspan="4"><a href="createform.php">Create New User</a></td>
  </tr>
  <?php
$query="SELECT `last`, `first`, `middle`, `email` FROM `bio` WHERE active=1 GROUP BY `email` ORDER BY `last`";
$result=mysqlquery($dbnames,$query);
$num=mysql_numrows($result);
for ($i=0; $i < $num; $i++) {
	$first=mysql_result($result,$i,"first");
	$middle=mysql_result($result,$i,"middle");
	$last=mysql_result($result,$i,"last");
	$email=mysql_result($result,$i,"email");
	if ($middle==""){
		$name="$first $last";
	}
	else {
		$name="$first $middle $last";
	}
	echo "<tr>
          <td>$name</td>
          <td>$dept</td>
          <td>$position</td>
          <td><a href=\"individual.php?email=$email\"><input type=\"button\" value=\"View...\"></a></td>
          <td><a href=\"createform.php?mod=1&email=$email\"><input type=\"button\" value=\"Edit...\"></a></td>
        </tr>";
}
?>
</table>
</body>
</html>
