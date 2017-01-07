<?php
###############################################################################
# $Id$
# Creates or modifies a emaillist rule in the 3m database
###############################################################################

require_once dirname(__FILE__).'/common.php';

$redirect_wait = 1; // Number of seconds to wait before redirecting if no error
$isupdate = isset($_POST[ruleid]);
$ruleid = $_POST[ruleid];

// First validate the form
$optionalVals = array("addlist", "notificationlist");
foreach($optionalVals as $reqval){
  if(isset($_POST[$reqval])) {
    $$reqval = $_POST[$reqval];
  } else {
    $$reqval = "";
  }
}

if(!(isset($_POST["dept"]) && formatYear($_POST["position"]))) {
  fatal("Need to specify dept and specify and position!");
} else {
  $dept = $_POST["dept"];
  $position = $_POST["position"];
}

if (isset($_POST["delete"])) {
   // Performing delete.
   error_log("Deleting emailrule " . $ruleid);
   $sql = "DELETE FROM emailrules ";
   $sql .= "WHERE ruleid=" . ((int)$ruleid);
   $res =& $mdb2->exec($sql);
   if(PEAR::isError($res)) {
       error_log($res->getDebugInfo());
       fatal("ERROR: Could not delete emailrule: ".$res->getMessage());
   }
}
// Validation complete. If this is an update, perform update! 
else if ($isupdate) {
  error_log("Updating emailrule " . $_POST["ruleid"]);
  $sql = "UPDATE emailrules SET dept=" . $mdb2->quote($dept) . ",";
  $sql .= " position=" . $mdb2->quote($position) . ",";
  $sql .= " addlist=" . $mdb2->quote($addlist) . ",";
  $sql .= " notificationlist=" . $mdb2->quote($notificationlist);
  $sql .= " WHERE ruleid=" . ((int)$ruleid);
  $res =& $mdb2->exec($sql);
  if(PEAR::isError($res)) {
    error_log($res->getDebugInfo());
    fatal("Could not update emailrule: ".$res->getMessage());
  }
} else {
  error_log("Creating new emailrule.");
  $sql = "INSERT INTO emailrules (dept, position, addlist, notificationlist) VALUES (";
  $sql .= $mdb2->quote($dept) . ", ";
  $sql .= $mdb2->quote($position) . ", ";
  $sql .= $mdb2->quote($addlist) . ", ";
  $sql .= $mdb2->quote($notificationlist) . ")";
  $res =& $mdb2->exec($sql);
  if(PEAR::isError($res)) {
    error_log($res->getDebugInfo());
    fatal("Could not add emailrule: ".$res->getMessage());
  }  
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="<?=$redirect_wait?>; url=rules.php">
</head>
<body onload="updateCounter();">
You have successfully modified the email rule. If, instead, you have fucked 
it up, e-mail <a href="mailto:techno@tech.mit.edu">techno@tech.mit.edu</a>.<br>
<a href="."> Click here to go back to 3M</a>. <span id="note"></span>
</body>
<script type="text/javascript" language="javascript">
  var time =<?=$redirect_wait?>;
  var text = "You will automatically be redirected in ";

  function updateCounter() {
    var s = "second";
    if(time != 1) { s += "s"; }
    if(time < 0 ) { window.location = "rules.php"; return; } 
    document.getElementById("note").innerHTML = text + time + " " + s;
    time--;
    setTimeout("updateCounter()", 1000);
  }
</script>
</html>
