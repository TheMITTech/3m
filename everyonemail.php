<?php
###############################################################################
# $Id$
# Prints out a list of all addresses that should be subscribed to 
# everyone@tech.mit.edu
###############################################################################

require_once dirname(__FILE__).'/common.php';

header('Content-Type: text/plain');
$sql = "SELECT DISTINCT email FROM staff WHERE active='yes' ORDER BY email";
$res =& $mdb2->query($sql);
if(PEAR::isError($res)) {
  error_log($res->getDebugInfo());
  fatal("Could not get everyone@tt membership list: ".$res->getMessage());
}
$res->bindColumn('email', $email);
while ($row =  $res->fetchRow()) {
  echo "$email\n";
}
