<?php
include 'config.php';
if(!isset($_POST["first"])) { // In case someone just goes to /create.php
  fatal("Incomplete form data. Please use createform.php");
}
$first = mysql_real_escape_string($_POST["first"]);
$middle = mysql_real_escape_string($_POST["middle"]);
$last = mysql_real_escape_string($_POST["last"]);
$email = mysql_real_escape_string($_POST["email"]);
$class = mysql_real_escape_string($_POST["class"]);
$gender = mysql_real_escape_string($_POST["gender"]);
$department = mysql_real_escape_string($_POST["department"]);
$job = mysql_real_escape_string($_POST["job"]);
$email = mysql_real_escape_string($_POST["email"]);
$month =mysql_real_escape_string( $_POST["month"]);
$day = mysql_real_escape_string($_POST["day"]);
$year = mysql_real_escape_string($_POST["year"]);
$bmonth = mysql_real_escape_string($_POST["bmonth"]);
$bday = mysql_real_escape_string($_POST["bday"]);
$byear = mysql_real_escape_string($_POST["byear"]);
/*
$emonth = $_POST["emonth"];
$eday = $_POST["eday"];
$eyear = $_POST["eyear"];
*/
$phone = mysql_real_escape_string($_POST["phone"]);
$aim = mysql_real_escape_string($_POST["aim"]);
$mod = mysql_real_escape_string($_POST["mod"]);
if(($class!=$gradYear)&&($class!=$cmeYear)&&($class!=$blankYear)&&($class<$minYear||$class>$maxYear)) {
  fatal("Invalid class year: $class");
}
for ($i=0;$i<strlen($phone); $i++){
	$currentdigit=substr($phone,$i,1);
	if(is_numeric($currentdigit)){
		$cleanphone="$cleanphone$currentdigit";
	}
}
if ($mod==1){
	$edate=date("Y/d/m", mktime(0, 0, 0, $month, $day-1, $year)); 
	$query="UPDATE `bio` SET `edate` = '$edate' WHERE `email` ='$email' AND `active` ='1'";
	mysqlquery($dbnames,$query);
	$query="UPDATE `bio` SET `active` = '0' WHERE `email` ='$email' AND `active` ='1'";
	mysqlquery($dbnames,$query);
}
if($_POST["delete"] != "Deactive me!") {
  $query="INSERT INTO bio VALUES ('','$first','$middle','$last','$class','$gender','$email','$department','$job','$year/$month/$day','','$byear/$bmonth/$bday','$cleanphone','$aim','1','1')";
  mysqlquery($dbnames,$query);
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="3; url=.">
</head>
<body>
You have successfully modified the staff system. If, instead, you have fucked it up, e-mail tenman@the-tech.mit.edu.<br>
<a href="."> Click here to go back</a>
</body>
</html>
