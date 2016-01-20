<?php 
###############################################################################
# $Id$
# Index page for 3m scripts. Shows a user listing.
###############################################################################

require_once dirname(__FILE__).'/common.php';

$sql = "SELECT display_name, staffid FROM staff WHERE active='yes' GROUP BY email ORDER BY last";
$res =& $mdb2->query($sql);
if(PEAR::isError($res)) { 
  error_log($res->getDebugInfo());
  fatal("Could not get staff listing: ".$res->getMessage()); 
}
$res->bindColumn('display_name', $name);
$res->bindColumn('staffid', $staffid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>The Tech's Mast and Mailing List Maintenance System</title>
</head>
<body>
<H1 align="center">Mast and Mailing List Maintenance System</h1>
<table align="center">
  <tr>
    <td colspan="4"><a href="createform.php">Create New User</a></td>
  </tr>
  <tr>
    <td><a href="rules.php">Manage email rules! (New in 2016!)</a></td>
  </tr>
  <tr>
    <td colspan="4"><a href="taggedTextMast.php">Download Tagged Text Masthead</a></td>
  </tr>
  <tr>
    <td colspan="4"><a href="htmlMast.php">HTML Masthead</a></td>
  </tr>
  <tr>
    <td colspan="4"><a href="manboardcontact.php">Manboard Contact List</a></td>
  </tr>
  <tr>
    <td colspan="4"><a href="everyonemail.php">Everyone's Email List</a></td>
  </tr>
  <tr>
    <td colspan="4"><a href="everyonephone.php">Everyone's Phone Number List</a></td>
  </tr>
<?php while ($row = $res->fetchRow()) { ?>
  <tr>
  <td><?=$name?></td> <td><!--dept--></td> <td><!--$position--></td> 
    <td><a href="individual.php?staffid=<?=$staffid?>"><input type="button" value="View..."></a></td>
    <td><a href="createform.php?staffid=<?=$staffid?>"><input type="button" value="Edit..."></a></td>
  </tr>
<? } ?>
</table>
</body>
</html>
