<?php 
###############################################################################
# $Id$
# Displays the manboard contact list
###############################################################################

require_once dirname(__FILE__).'/common.php';

$sql ="SELECT display_name, dept, position, email, phone FROM staff JOIN ";
$sql .= "departments ON (staff.dept=departments.name) JOIN titles ON (staff.";
$sql .= "position=titles.name) WHERE active='yes' and titles.isManboard=";
$sql .= "'yes' ORDER BY departments.order, titles.order, staff.last ASC";
$res =& $mdb2->query($sql);
if(PEAR::isError($res)) {
  error_log($res->getDebugInfo());
  fatal("Could not get manboard members: " . $res->getMessage());
}
$res->bindColumn("display_name", $name);
$res->bindColumn("dept", $dept);
$res->bindColumn("position", $position);
$res->bindColumn("email", $email);
$res->bindColumn("phone", $phone);
?>
<html>
<head><title>The Tech Manboard Contact List</title></head>
<body>
<H1 align="center">The Tech</H1>
<H2 align="center">Manboard Contact List</H2>
<table align="center">
<tr>
  <th>Name</th><th>Department</th><th>Position</th><th>E-mail</th> <th>Phone</th>
<?php $i=0; while ($row = $res->fetchRow()) { ?>
<tr<?=$i%2==0 ? '' : ' style="background-color:#f0f0f0"';$i++?>>
<td><?=$name?></td><td><?=$dept?></td><td><?=$position?></td><td><a href="mailto:<?=$email?>"><?=$email?></a></td><td><?=formatPhone($phone)?></td></tr>
</tr>
<?php } ?>
</table>
</body></html>
