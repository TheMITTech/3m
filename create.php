<?php
###############################################################################
# $Id$
# Creates or modifies a user in the 3m database
###############################################################################

require_once dirname(__FILE__).'/common.php';

$redirect_wait = 5; // Number of seconds to wait before redirecting if no error

// First validate the form
$required = array("first", "last", "email", "dept", "position");
foreach($required as $reqval) {
  if(!isset($_POST[$reqval])) {
    fatal("Required field is missing: $reqval.");
  }
  if($_POST[$reqval] == '' and 
    !($_POST["dept"] == "Advisory Board" and $reqval = "position")) {
    fatal("Required field is empty: $reqval.");
  }
}
if(isset($_POST["year"]) && formatYear($_POST["year"]) == NULL) {
  fatal("Invalid class year: " . $_POST["year"]);
}
if(isset($_POST["phone"])) {
  $_POST["phone"] = preg_replace("/[^0-9]/", "", $_POST["phone"]);
}
if(isset($_POST["begin_date"])) {
  $timestamp = strtotime($_POST["begin_date"]);
  if($timestamp === FALSE) {
    fatal("Invalid begining date: " . $begin_date);
  }
  $_POST["begin_date"] = date("Y-m-d", $timestamp);
}
if(isset($_POST["birthday"])) {
  $timestamp = strtotime($_POST["birthday"]);
  if($timestamp === FALSE) {
    $_POST["birthday"] = ''; // Not a fatal error, just unset the birthday
  } else {
    $_POST["birthday"] = date("Y-m-d", $timestamp);
  }
}

// Validation complete. If this is an update, disable their old information
if (isset($_POST['update'])) {
  error_log("Updating " . $_POST["display_name"]);
  $sql = "UPDATE staff SET end_date=CURDATE(), active='no' WHERE email=";
  $sql .= $mdb2->quote($_POST["email"]) . " AND active = 'yes'";
  $res =& $mdb2->exec($sql);
  if(PEAR::isError($res)) {
    error_log($res->getDebugInfo());
    fatal("Could not update user: ".$res->getMessage());
  }
}

// If they're being deleted, we simply don't reactivate them
if($_POST["delete"] != "Deactive me!") {
  // The HTML field names must be the same as the SQL column names for this to 
  // work
  $fields = array("first", "middle", "last", "display_name", "year", "gender",
    "email", "dept", "position", "begin_date", "athena_username", "birthday", 
    "phone");
  $values = array();
  foreach ($fields as $f) { array_push($values, $mdb2->quote($_POST[$f])); }
  $sql = "INSERT INTO staff (" . join(", ", $fields) . ") VALUES (";
  $sql .= join(", ", $values) . ")";
  $res =& $mdb2->exec($sql);
  if(PEAR::isError($res)) {
    error_log($res->getDebugInfo());
    fatal("Could not insert user: ".$res->getMessage());
  }
} ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="<?=$redirect_wait?>; url=.">
</head>
<body onload="updateCounter();">
You have successfully modified the staff system. If, instead, you have fucked 
it up, e-mail <a href="mailto:techno@tech.mit.edu">techno@tech.mit.edu</a>.<br>
<a href="."> Click here to go back to 3M</a>. <span id="note"></span>
</body>
<script type="text/javascript" language="javascript">
  var time =<?=$redirect_wait?>;
  var text = "You will automatically be redirected in ";

  function updateCounter() {
    var s = "second";
    if(time != 1) { s += "s"; }
    if(time < 0 ) { window.location = "."; return; } 
    document.getElementById("note").innerHTML = text + time + " " + s;
    time--;
    setTimeout("updateCounter()", 1000);
  }
</script>
</html>
