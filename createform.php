<?php
###############################################################################
# $Id$
# UI to create.php
###############################################################################

require_once dirname(__FILE__).'/common.php';

$isUpdate = isset($_GET["staffid"]);

if ($isUpdate) {
  $fields = array("first", "middle", "last", "display_name", "year", "gender",
    "email", "dept", "position", "begin_date", "end_date", "athena_username", 
    "birthday", "phone");
  $sql = "SELECT " . join(", ", $fields) . " FROM staff WHERE staffid = ";
  $sql .= $mdb2->quote($_GET["staffid"]) . " AND active = 'yes'";
  $mdb2->setLimit(1);
  $res =& $mdb2->query($sql);
  if(PEAR::isError($res)) {
    error_log($res->getDebugInfo());
    fatal("Could not get information for $email: ".$res->getMessage());
  }
  foreach ($fields as $field) {
    $res->bindColumn($field, $$field);
  }
  $row = $res->fetchRow();
  $phone = formatPhone($phone);
  if($birthday == '0000-00-00') { $birthday = ''; }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if ($isUpdate) { ?>
<title>The Tech Staff Modification Page</title>
<?php } else { ?>
<title>The Tech Staff Addition Page</title>
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
<form method="post" action="./create.php">
  <table width="50%" border="0" align="center">
    <tr>
    <td>
      <input onkeyup="updateDisplayName()" type="text" size="15" maxlength="20" name="first" value="<?=$first?>">
    </td>
    <td>
      <input onkeyup="updateDisplayName()" type="text" size="15" maxlength="20" name="middle" value="<?=$middle?>">
    </td>
    <td>
      <input onkeyup="updateDisplayName()" type="text" size="15" maxlength="30" name="last" value="<?=$last?>">
    </td>
    </tr>
    <tr>
      <td>First Name</td>
      <td>Middle Name</td>
      <td>Last Name</td>
    </tr>
    <tr>
      <td colspan="1">Display Name</td>
      <td colspan="2">
      <input onchange="flagDisplayNameChanged()" type="text" size="30" maxlength="50" name="display_name" value="<?=$display_name?>">
      </td>
    </tr>
    <tr>
      <td>
        <input onkeyup="updateAthenaUsername()" type="text" size="15" maxlength="50" name="email" value="<?=$email?>">
      </td>
      <td>
        <input type="text" size="4" maxlength="4" name="year" value="<?=$year?>">
      </td>
      <td>
        Male <input type="radio" value="Male" name="gender"<?=$gender=="male"?" checked":""?>><br/>
        Female <input type="radio" value="Female" name="gender"<?=$gender=="female"?" checked":""?>><br />
	Other <input type="radio" value="Other" name="gender" <?=$gender=="other"?" checked":""?>>
      </td>
    </tr>
    <tr>
      <td>E-Mail Address</td>
      <td>Class Year (YYYY)
      <font size="1"><br />0 for Grad
      <br />9998 for CME
      <br />9999 for no year</font></td>
      <td></td>
    </tr>
    <tr>
      <td>
        <select onchange="updateTitles()" name="dept">
        <option<?=isset($dept)?"":" selected "?>></option>
<?php foreach (getDepartments() as $department) { ?>
        <option value="<?=$department?>"<?=($dept==$department)?" selected":""?>><?=$department?></option>
<?php } ?>
        </select>
      </td>
      <td>
        <select name="position">
<?php if(isset($dept)) { foreach (getDepartmentTitles($dept) as $title) { ?>
        <option value="<?=$title?>"<?=($position==$title)?" selected":""?>><?=$title?></option>
<?php } } else { ?>
        <!-- This will be filled in with JavaScript -->
        <option></option>
<?php } ?>
        </select>
      </td>
      <td>
        <input type="text" name="begin_date" size="10" maxlength="10" value="<?=date("Y-m-d")?>">
      </td>
    </tr>
    <tr>
      <td>Department</td>
      <td>Job Title </td>
      <td>Effective Date<br><font size=1>(YYYY-MM-DD)</font></td>
    </tr>
    <tr>
      <td>
        <input type="text" name="birthday" size="10" maxlength="10" value="<?=$birthday?>">
      </td>
      <td>
        <input type="text" size="15" maxlength="20" name="phone" value="<?=$phone?>">
      </td>
      <td>
        <input onchange="flagAthenaUserChanged()" type="text" size="15" maxlength="20" name="athena_username" value="<?=$athena_username?>">
      </td>
    </tr>
    <tr>
      <td> Birthday<br><font size=1>(YYYY-MM-DD)</font></td>
      <td> Phone Number<br><font size=1>(XXX-XXX-XXXX)</font></td>
      <td> Athena Username</td>
    </tr>
  </table>
<?php if ($isUpdate) { ?>
  <input type="hidden" name="staffid" value="<?=$_GET["staffid"]?>">
<?php } ?>
    <p align="center">
    <input style="margin-right:10%" type="reset" value="Reset">
    <input type="submit" value="Submit" name="submit">
    <input style="margin-left:10%" type="submit" value="Deactive me!" name="delete">
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

function updateDisplayName() {
  if(!displayNameModified) {
    var first = document.getElementsByName("first")[0].value;
    var middle= document.getElementsByName("middle")[0].value;
    var last  = document.getElementsByName("last")[0].value;

    var name = first + " ";
    if(middle.length != 0) {
      name += middle.charAt(0) + ". ";
    }
    name += last;

    document.getElementsByName("display_name")[0].value = name;
  }
  return true;
}

function flagDisplayNameChanged() {
  displayNameModified = true;
}

function updateAthenaUsername() {
  if(!athenaUserModified) {
    var email = document.getElementsByName("email")[0].value;
    var i = email.indexOf("@mit.edu",1);
    if(i != 0) {
      document.getElementsByName("athena_username")[0].value = email.substring(0,i);
    }
  }
  return true;
}

function flagAthenaUserChanged() {
  athenaUserModified = true;
}

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
