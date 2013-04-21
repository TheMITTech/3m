<?php
###############################################################################
# $Id$
# Common functions to all of the 3m scripts.
###############################################################################

require_once dirname(__FILE__).'/config.php';

### Defined Functions
function fatal($message) {
  @header("HTTP/1.1 500 Internal Server Error");
  @header("Content-type: text/plain");
  error_log($message);
  die($message." \nE-mail techno@tech.mit.edu if this problem persists.");
}

# Takes a year and returns the appropriately formated data
# $quote type should either be "UTF-8" or "html"
function formatYear($year, $quote = 'UTF-8') {
  global $min_class_year, $max_class_year, $class_year_map;
  $utf8_quote = "\xe2\x80\x99";
  $html_quote = "&rsquote;";
  $quoteSymbol = ($quote == 'html' ? $html_quote : $utf8_quote);
  $year = intval($year);
  if ($year > $min_class_year && $year < $max_class_year) {
    return($quoteSymbol . substr($year, 2));
  } else {
    # NULL if $year not in $class_year_map!
    return $class_year_map[$year];
  }
}

# Formats a 10 digit phone number. We better be using 10 digit phone numbers
# Fuck internationalzation
function formatPhone($phone) {
  return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
}

# Queries the databases for all members of the specified department
# Returns an array of position to array of formatted members
# i.e. "Position" => array("Tech Staffer ’81", "Been There Done That Colen"); 
function getDepartmentMembers($department) {
  global $mdb2;
  $dquote = $mdb2->quote($department);
  $sql = <<<EOF
SELECT display_name, year, position FROM staff
JOIN departments ON (staff.dept=departments.name)
JOIN titles ON (staff.position=titles.name)
WHERE
  staff.dept=$dquote AND 
  staff.active = "yes"
ORDER BY titles.order, staff.year, staff.last, staff.first, staff.middle ASC
EOF;
  $res =& $mdb2->query($sql);
  if(PEAR::isError($res)) {
    error_log($res->getDebugInfo());
    fatal("Could not get department members: " . $res->getMessage());
  }
  $res->bindColumn("display_name", $name);
  $res->bindColumn("year", $year);
  $res->bindColumn("position", $position);

  $ret = array();
  while ($row = $res->fetchRow()) {
    $formattedYear = formatYear($year);
    if ($formattedYear) {
      $person = $name .
        mb_convert_encoding('&nbsp;', 'UTF-8', 'HTML-ENTITIES') .
	formatYear($year);
    } else {
      //trim off space if no year
      $person = $name;
    }
    if(isset($ret[$position])) {
      array_push($ret[$position], $person);
    } else {
      $ret[$position] = array($person);
    }
  }
  return $ret;
}

# Returns an array of Titles valid for the given department
function getDepartmentTitles($department) {
  global $mdb2;
  $sql = 'SELECT name FROM titles WHERE FIND_IN_SET((SELECT shortname FROM ';
  $sql .= 'departments WHERE name = ' . $mdb2->quote($department) . '), '; 
  $sql .= 'inDepartment) > 0 AND isActive = "yes" ORDER BY titles.order ASC';
  $res =& $mdb2->query($sql);
  if(PEAR::isError($res)) {
    error_log($res->getDebugInfo());
    fatal("Could not get title names: " . $res->getMessage());
  }
  return $res->fetchCol();
}

# Returns an array of Department Names
function getDepartments() {
  global $mdb2;
  $sql = 'SELECT name FROM departments WHERE isActive = "yes" ORDER BY ';
  $sql .= 'departments.order ASC';
  $res =& $mdb2->query($sql);
  if(PEAR::isError($res)) {
    error_log($res->getDebugInfo());
    fatal("Could not get department names: " . $res->getMessage());
  }
  return $res->fetchCol();
}

$mdb2 =& MDB2::connect("mysql://$db_user:$db_pass@$db_host/$db_name");
if(PEAR::isError($mdb2)) {
  error_log($mdb2->getDebugInfo());
  fatal("Could not connect to database: ".$mdb2->getMessage());
}

# We care about empty strings. Supposedly Oracle thinks "" and NULL are the same
$mdb2->setOption('portability', 
  MDB2_PORTABILITY_ALL ^ MDB2_PORTABILITY_EMPTY_TO_NULL);


$res =& $mdb2->query('SET NAMES utf8'); # Use UTF-8 for communication
if(PEAR::isError($res)) {
  error_log($mdb2->getDebugInfo());
}
