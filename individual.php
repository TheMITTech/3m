<?php 
###############################################################################
# $Id$
# Shows the record of an individual in 3m.
###############################################################################

require_once dirname(__FILE__).'/common.php';

$staffid = $_GET["staffid"];
$fmt = "j F Y";

// Get information for the header
$sql = "SELECT display_name, email, phone, birthday, gchat FROM staff ";
$sql .= "WHERE ";
# Removed limitation to active staff members  -- email addreses
# are ~never recycled given an IS&T policy decision in the late 1990s,
# so, there is no fear of confusion. This makes it possible to more easily
# see history of departed staff.
# $sql .= "active='yes' AND ";
$sql .= "staffid=".$mdb2->quote($staffid);
$sql .= " ORDER BY active ASC";
$res =& $mdb2->query($sql);
if(PEAR::isError($res)) {
  error_log($res->getDebugInfo());
  fatal("Could not get detailed info for $email: ".$res->getMessage());
}
$row = $res->fetchRow();
$name = $row[0];
$email= $row[1];
$phone= preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $row[2]);
$birthday = $row[3] == 0 ? "Not set" : date($fmt, strtotime($row[3]));
$gchat = $row[4];

// Get each position's information
$sql = "SELECT dept, position, UNIX_TIMESTAMP(begin_date) as begin_date, ";
$sql .= " UNIX_TIMESTAMP(end_date) as end_date FROM staff WHERE email=";
$sql .= $mdb2->quote($email) . " ORDER BY end_date, active DESC";
$res =& $mdb2->query($sql);
if(PEAR::isError($res)) {
  error_log($res->getDebugInfo());
  fatal("Could not get detailed info for $email: ".$res->getMessage());
}
$res->bindColumn('dept', $dept);
$res->bindColumn('position', $position);
$res->bindColumn('begin_date', $begin_date, 'integer');
$res->bindColumn('end_date', $end_date, 'integer');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>The Tech's Mast and Mailing List Maintenance System</title>
</head>
  <h1 align="center">Staff History Report for <?=$name?></h1>
  <table>
    <tr><td colspan=2><a href="./">&lt;&lt; Go Back to Main Page</a></td></tr>
    <tr><th style='text-align: right'>E-mail:</th><td><?=$email?></td>
    <tr><th style='text-align: right'>gchat:</th><td><?=$gchat?></td>
    <tr><th style='text-align: right'>Phone:</th><td><?=$phone?></td>
    <tr><th style='text-align: right'>Birthday:</th><td><?=$birthday?></td>
    <tr><th style='text-align: right'>Facebook:</th><td><a href="http://www.facebook.com/search/?q=<?=$name?>">Search for Them</a></td>
  <body>
    <table>
    <tr>
      <th> Dates </th>
      <th> Department </th>
      <th> Position </th>
    </tr>
<?php while ($row = $res->fetchRow()) { ?>
    <tr>
      <td><?php
  echo date($fmt, $begin_date) . " &ndash; ";
  echo $end_date === 0 ? "Present" : date($fmt, $end_date);
?>
</td> <td><?=$dept?></td> <td><?=$position?></td>
    </tr>
<?php } ?>
</table>
</body>
</html>
