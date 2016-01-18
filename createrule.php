<?php
###############################################################################
# $Id$
# UI to create.php
###############################################################################

require_once dirname(__FILE__).'/common.php';

$isUpdate = isset($_GET["id"]);

$fields = array("dept", "position", "addlist", "notificationlist");

f ($isUpdate) {
  $ruleid = $_GET["id"];
  $sql = "SELECT " . join(", ", $fields) . " FROM listaddrules WHERE id = " . $mdb2->quote(ruleid);
  $mdb2->setLimit(1);
  $res =& $mdb2->query($sql);
  if(PEAR::isError($res)) {
    error_log($res->getDebugInfo());
    fatal("Could not get information for $ruleid: ".$res->getMessage());
  }
  foreach ($fields as $field) {
    $res->bindColumn($field, $$field);
  }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if ($isUpdate) { ?>
<title>The Tech Mailing List Rule Modification Page</title>
<?php } else { ?>
<title>The Tech Mailing List Rule Addition Page</title>
<?php } ?>
<style type="text/css">
td { padding:2px;text-align:center }
</style>
</head>
<body style='margin:15px auto;padding:5px 20px;width:860px;'>
<h1 align="center">The Tech</h1>
<?php if ($isUpdate) { ?>
<h2 align="center">Staff Modification Page</h2>
<?php } else { ?>
<h2 align="center">Staff Addition Page</h2>
<?php } ?>
<form method="post" action="./createrule.php">
      <select onchange="updateTitles()" name="dept">
<?php foreach (getDepartments() as $department) { ?>
        <option value="<?=$department?>"<?=($dept==$department)?" selected":""?>><?=$department?></option>
<?php } ?>
        </select>
        <select name="position">
<?php if(isset($dept)) { foreach (getDepartmentTitles($dept) as $title) { ?>
        <option value="<?=$title?>"<?=($position==$title)?" selected":""?>><?=$title?></option>
<?php } } else { ?>
        <!-- This will be filled in with JavaScript -->
        <option></option>
<?php } ?>
        </select>

<?php if ($isUpdate) { ?>
  <input type="hidden" name="update" value="<?=($_GET['ID'])?>">
<?php } ?>
    <p align="center">
    <input style="margin-right:10%" type="reset" value="Reset">
    <input type="submit" value="Submit" name="submit">
    <input style="margin-left:10%" type="submit" value="Delete!" name="delete">
  </p>
  <br>
</form>
<form method="get" action="./">
<input style="width:50%;margin-left:25%;margin-right:25%" type="submit" value="Cancel">
</form>
</body>
<script type="text/javascript" language="javascript">
var displayNameModified = false;
var athenaUserModified  = false;

function updateTitles() {
  var url = "./json.php?0=getDepartmentTitles&1=";
  url += document.getElementsByName("dept")[0].value;
  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else { // IE6 and IE 5
    alert("Get rid of your stanky old shit. http://www.getfirefox.com/");
    return false;
  }
  xmlhttp.open("GET",url,false);
  xmlhttp.send(null);
  var titles = JSON.parse(xmlhttp.responseText);
  var html = "";
  for (t in titles) {
    html += "<option value='" + titles[t] + "'>" + titles[t] + "</option>\n";
  }
  document.getElementsByName("position")[0].innerHTML = html;
}
</script>
</html>
