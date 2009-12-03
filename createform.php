<?php
include 'config.php';
$mod = $_GET["mod"];
$email = $_GET["email"];
echo'
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
';
if ($mod==1){
	echo '<title>The Tech Staff Modification Page</title>';
$query="SELECT *  FROM `bio` WHERE `email` = \"$email\" AND `active` = 1";
$result=mysqlquery($dbnames,$query);
$first = mysql_result($result,0,"first");
$middle = mysql_result($result,0,"middle");
$last = mysql_result($result,0,"last");
$class = mysql_result($result,0,"year");
$gender = mysql_result($result,0,"gender");
$email = mysql_result($result,0,"email");
$department = mysql_result($result,0,"dept");
$job = mysql_result($result,0,"position");
$bdate = mysql_result($result,0,"bdate");
$edate = mysql_result($result,0,"edate");
$birthday = mysql_result($result,0,"birthday");
$phone = mysql_result($result,0,"phone");
$aim = mysql_result($result,0,"aim");
$gender = mysql_result($result,0,"gender");
/*
$eyear=substr($edate,2,2);
$emonth=substr($edate,5,2);
$eday=substr($edate,8,2);
*/
$year=substr($bdate,2,2);
$month=substr($bdate,5,2);
$day=substr($bdate,8,2);

$byear=substr($birthday,2,2);
$bmonth=substr($birthday,5,2);
$bday=substr($birthday,8,2);
if ($gender=='M'){
	$male="checked";
}
else if ($gender=='F'){
	$female="checked";
}
}
else {
	echo '<title>The Tech Staff Addition Page</title>';
}
echo'
<SCRIPT LANGUAGE="JavaScript">
depts = new Array(';
$query="SHOW TABLES IN ttdepartments";
$result=mysqlquery($dbdepts,$query);
$num=mysql_numrows($result);
for ($i=0; $i < $num; $i++) {
	echo 'new Array(new Array("Please Select Position",0),';
	$currentdept=mysql_result($result,$i);
        // id #1 is actually the name of the department on the masthead
        // but it should never be used for a staff member's position
	$query="SELECT * FROM `$currentdept` where id != 1";
	$deptresult=mysqlquery($dbdepts,$query);
	$deptnum=mysql_numrows($deptresult);
	for ($j=0; $j < $deptnum; $j++) {
		$positions = mysql_result($deptresult,$j,"positions");
		echo 'new Array("';
		echo "$positions";
		echo '","';
		echo "$positions";
		echo '"';		
		if ($j==($deptnum-1)){
			echo ')';
		}
		else {
			echo '),';
		}
              }
	if ($i==($num-1)){
		echo ')';
	}
	else {
		echo '),';
	}

}
echo ');
function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
var i, j;
var prompt;
// empty existing items
for (i = selectCtrl.options.length; i >= 0; i--) {
selectCtrl.options[i] = null; 
}
prompt = (itemArray != null) ? goodPrompt : badPrompt;
if (prompt == null) {
j = 0;
}
else {
selectCtrl.options[0] = new Option(prompt);
j = 1;
}
if (itemArray != null) {
// add new items
for (i = 0; i < itemArray.length; i++) {
selectCtrl.options[j] = new Option(itemArray[i][0]);
if (itemArray[i][1] != null) {
selectCtrl.options[j].value = itemArray[i][1]; 
}
j++;
}
// select first item (prompt) for sub list
selectCtrl.options[0].selected = true;
   }
}

function init(dept, pos) {
  var index=depts.length;
  select = document.getElementById("selectDept");
  for(i=0; i<select.options.length; i++) {
    if(select.options[i].value == dept) {
      select.selectedIndex = i;
      index = i;
    }
  }
  select = document.getElementById("selectPos");
  fillSelectFromArray(select, depts[index-1]);
  for(i=0; i<select.options.length; i++) {
    if(select.options[i].value == pos) select.selectedIndex = i;
  }
}
</script>
</head>';
if($mod == 1) {
  echo "<body onload=\"init('$department', '$job');\" style='width: 50%'>";
} else {
  echo "<body style='width: 50%'>";
}
echo '<H1 align="center">The Tech</H1>';
if ($mod==1){
	echo '<H2 align="center">Staff Modification Page</H2>';
}
else {
	echo '<H2 align="center">Staff Addition Page</H2>';
}
echo'<form method="post" action="create.php">
  <table width="50%" border="0" align="center">
    <tr>
      <td><div align="center">
          <input type="text" size="15" maxlength="30" name="first" value="';
	echo "$first";
	echo '">
        </div></td>
      <td><div align="center">
          <input type="text" size="15" maxlength="30" name="middle" value="';
	echo "$middle";
	echo '">
        </div></td>
      <td><div align="center">
          <input type="text" size="15" maxlength="30" name="last" value="';
	echo "$last";
	echo '">
        </div></td>
    </tr>
    <tr>
      <td><div align="center">First Name</div></td>
      <td><div align="center">Middle Name</div></td>
      <td><div align="center">Last Name</div></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td><div align="center">
          <input type="text" size="20" maxlength="50" name="email" value="';
	echo "$email";
	echo '">
        </div></td>
      <td><div align="center">
          <input type="text" size="3" maxlength="4" name="class" value="';
	echo "$class";
	echo '">
        </div></td>
      <td><div align="center"> Male
          <input type="radio" value="Male" name="gender" ';
	echo $male;
	echo '">
          Female
          <input type="radio" value="Female" name="gender" ';
	echo $female;
	echo '">
        </div></td>
    </tr>
    <tr>
      <td><div align="center">E-Mail Address </div></td>
      <td><div align="center">Year</div></td>
      <td><div align="center">Gender</div></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td><div align="center">
         <SELECT ID="selectDept" NAME="department" onChange="fillSelectFromArray(this.form.job, ((this.selectedIndex == -1) ? null : depts[this.selectedIndex-1]));">
<OPTION VALUE="-1">Select Department';
$query="SHOW TABLES IN ttdepartments";
$result=mysqlquery($dbdepts,$query);
$num=mysql_numrows($result);
for ($i=0; $i < $num; $i++) {
	$currentdept=ucwords(mysql_result($result,$i));
        // zozer thought the zzzzzz table is a good way to store the order
        // of the departments on the masthead.
        if($currentdept=='Zzzzzz') continue;
	echo '<OPTION VALUE="';
	echo "$currentdept";
	echo '">';
	echo "$currentdept";
}
echo '</SELECT>
        </div></td>
      <td><div align="center">
<SELECT ID="selectPos" NAME="job">'."\n";
echo '<OPTION></OPTION>';
echo '</SELECT>

        </div></td>
      <td><div align="center">
          <input type="text" size="2" maxlength="2" name="month" value="';
	echo date('m');
	echo '">
          /
          <input type="text" size="2" maxlength="2" name="day" value="';
	echo date('d');
	echo '">
          /
          <input type="text" size="2" maxlength="2" name="year" value="';
	echo date('y');
	echo '">
        </div></td>
    </tr>
    <tr>
      <td><div align="center">Department</div></td>
      <td><div align="center">Job Title </div></td>
      <td><div align="center">Effective Date (mm/dd/yy)</div></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td><div align="center">
          <input type="text" size="2" maxlength="2" name="bmonth" value="';
	echo "$bmonth";
	echo '">
          /
          <input type="text" size="2" maxlength="2" name="bday" value="';
	echo "$bday";
	echo '">
          /
          <input type="text" size="2" maxlength="2" name="byear" value="';
	echo "$byear";
	echo '">
        </div></td>
      <td><div align="center">
          <input type="text" size="20" maxlength="30" name="phone" value="';
	echo "$phone";
	echo '">
        </div></td>
      <td><div align="center">
          <input type="text" size="20" maxlength="30" name="aim" value="';
	echo "$aim";
	echo '">
        </div></td>
    </tr>
    <tr>
      <td><div align="center"> Birthday (If you would like us to remember)</div></td>
      <td><div align="center"> Phone Number (digits only, please)</div></td>
      <td><div align="center"> Screenname</div></td>
    </tr>
<!--    <tr>
     <td colspan="3"><div align="center">
          <input type="text" size="2" maxlength="2" name="emonth" value="';
	echo date('m');
	echo '">
          /
          <input type="text" size="2" maxlength="2" name="eday" value="';
	$etempdate=date('d')-1;
	echo "";
	echo '">
          /
          <input type="text" size="2" maxlength="2" name="eyear" value="';
	echo date('y');
	echo '">
        </div></td>
</tr>
    <tr>
     <td colspan="3"><div align="center">
End Date
</td>
</tr>
-->
  </table>';
  if ($mod==1){
	echo '<input type="hidden" name="mod" value="1">';
	}
	echo'<p align="center">
    <input type="submit" value="Submit" name="submit">
    <input style="float:right" type="submit" value="Deactive me!" name="delete">
  </p>
  <br>
</form>';
?>
