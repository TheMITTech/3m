<?php
###############################################################################
# $Id$
# Generates HTML (and PHP) code to display the masthead on our website
###############################################################################

require_once dirname(__FILE__).'/common.php';

# Takes position title and array of members, returns formatted text
function formatPosition($title, $members) {
  $seperator = ",\n";
  if ($title == '') return join($seperator, $members); // For AdBoard
  if (sizeof($members) > 1 and !preg_match("/Staff$/", $title)) {
    $title = $title . (substr($title, -1) == "s" ? "es" : "s");
  }
  return "<b>$title:</b>\n" . join($seperator, $members);
}

# Takes department name and array or positions to array of members and returns 
# formatted text
function formatDepartment($name, $positions) {
  // Editors at Larger and AdBoard don't modify their dept names
  if(!($name == "Editors at Large" 
    or $name == "Advisory Board" 
    or $name == "Production Staff for This Issue")) {
    $name = $name . " Staff";
  }
  $folded = array();
  foreach ($positions as $title => $members) {
    array_push($folded, formatPosition($title, $members));
  }
  $folded = join(";\n", $folded);
  return "<div class='department'><h2>$name</h2>\n" .
    "<div>$folded.\n</div></div>";
}
$depts = getDepartments();
// Exec board gets special formatting
$exec = getDepartmentMembers(array_shift($depts));

header('Content-type: text/html; charset=utf-8');
// The first thing we want in our output is actually php, so we'll need a print
// block to play nicely

<<< IGNOREUNTIL

print <<< EOF
<?
require("../lib/includes/the-tech.php");
\$about_active="on-";
\$a_staff_active="active";
\$title_suffix="Our Staff";
\$page_height="1180px";
require("../lib/templates/header.php");
require("./sidebar.html");
?>
EOF

IGNOREUNTIL
?>

<link rel="stylesheet" href="masthead.css" type="text/css">

<div id="main" style="float:right;width:660;border-right: 0px;padding-top:0;">
<?php foreach ($exec as $position => $members) { ?>
<div class="exec">
<h2><?=$position?></h2> <div><?=$members[0]?></div>
</div>
<?php } 
// Now deal with the rest of the departments
foreach ($depts as $dept) {
  $members = getDepartmentMembers($dept);
  if(sizeof($members) == 0) continue;
  echo formatDepartment($dept, $members) . "\n";
}

// Time for the copyright with the current year
$currentyear=date("Y");
?>
<p id="bottom-matter"><i>The Tech</i> (ISSN 0148-9607) is published on
Thursdays during the academic year (except during MIT
vacations) and monthly during the summer by
The Tech, Room W20-483, 84 Massachusetts Avenue, Cambridge, Mass. 02139.
Subscriptions are $50.00 per year (third class). <strong>Postmaster:</strong> 
Please send all address changes to our mailing address: The Tech, P.O. Box 
397029, Cambridge, Mass. 02139-7029. <strong>Telephone:</strong> Editorial: 
(617)&nbsp;253-1541. Business: (617)&nbsp;258-8324. Facsimile: 
(617)&nbsp;258-8226. <em>Advertising, subscription, and typesetting rates 
available.</em> Entire contents <strong>&copy;&nbsp;<?=$currentyear?> The 
Tech</strong>. <em>Printed by Turley Publications, Inc.</em></p>

<p id="last-updated">This masthead was last updated on <?=date("F j, Y")?>.</p>

</div>
<?
// Like the beginning, we end with PHP code
print <<< EOF
<?require("../lib/templates/footer.php");

EOF
?>
