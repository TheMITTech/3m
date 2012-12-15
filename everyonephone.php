<?php
###############################################################################
# Prints out a list of all phone numbers of people that should be subscribed to 
# everyone@tech.mit.edu
###############################################################################

require_once dirname(__FILE__).'/common.php';

header('Content-Type: text/plain');
# $sql = "SELECT DISTINCT email FROM staff WHERE active='yes' ORDER BY email";
$sql = "SELECT DISTINCT display_name, phone FROM staff WHERE active = 'yes' ";
$sql .= "ORDER BY last";
$res =& $mdb2->query($sql);
if(PEAR::isError($res)) {
  error_log($res->getDebugInfo());
  fatal("Could not get everyone@tt membership list: ".$res->getMessage());
}

$res->bindColumn('display_name', $name);
$res->bindColumn('phone', $phone);
while ($row =  $res->fetchRow()) {
      echo "$name: ";
      echo formatPhone($phone);
      echo "\n";
}
