<?php
###############################################################################
# $Id$
# Provides JSON interface to common functions.
# This shouldn't be a security risk as long as it's internally accessible only
###############################################################################

require_once dirname(__FILE__).'/common.php';

if(isset($_GET["0"])) {
  $function = $_GET["0"];
  $args = array();
  for($i=1; isset($_GET["$i"]); $i++) {
    array_push($args, $_GET["$i"]);
  }
  if(function_exists($function)) {
    $ret = call_user_func_array($function, $args);
  }
}

header("Content-type: application/json");
echo json_encode($ret);
