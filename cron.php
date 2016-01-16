<?php 
###############################################################################
# $Id$
# Script to run daily tasks
###############################################################################

require_once dirname(__FILE__).'/common.php';

# Mails a list of today's birthdays to the chairman
function getBirthdays() {
  global $mdb2;

  # Who is the current chairman?
  $sql = "SELECT display_name FROM staff WHERE position = 'Chairman' AND " .
    "active = 'yes'";
  $res =& $mdb2->query($sql);
  if(PEAR::isError($res)) { 
    error_log($res->getDebugInfo());
    fatal("Could not get chairman's name: ".$res->getMessage()); 
  }
  $chairman = $res->fetchOne();
  $chairtok = explode(" ", $chairman);
  $res->free();

  # Get today's Birthdays
  $sql = "SELECT display_name, YEAR(CURDATE()) - YEAR(birthday) AS age FROM " .
    "staff WHERE MONTH(CURDATE()) = MONTH(birthday) AND DAY(CURDATE()) = " .
    "DAY(birthday) AND active = 'yes' GROUP BY display_name ORDER BY " .
    "birthday ASC";
  $res =& $mdb2->query($sql);
  if(PEAR::isError($res)) { 
    error_log($res->getDebugInfo());
    fatal("Could not get today's birthdays: ".$res->getMessage()); 
  }
  $rows = $res->numRows;
  if ( $rows == 0 ) {
    $res->free();
    return;
  }
  $res->bindColumn('display_name', $name);
  $res->bindColumn('age', $age);

  # Form mail message
  $message = "Hi $chairtok[0],\n\n";
  while ($row = $res->fetchRow()) {
    $message .= "  $name is $age today.\n";
  }
  $message .= "\nPlease send them birthday wishes!\n\n";
  $message .= "Sincerely,\n\nThe Masthead and Maillist Maintenance System\n";

  # Mail the results
  mail("$chairman <rram@tech.mit.edu>", "Today's Birthday Reminders", 
    $message, "From: 3M Birthday Reminder <techno@tech.mit.edu>");
} // end of getBirthdays()

### Run Commands
getBirthdays();
