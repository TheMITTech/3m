<?php
include 'config.php';
$first = $_POST["first"];
$middle = $_POST["middle"];
$last = $_POST["last"];
$email = $_POST["email"];
$class = $_POST["class"];
$gender = $_POST["gender"];
$department = $_POST["department"];
$job = $_POST["job"];
$email = $_POST["email"];
$month = $_POST["month"];
$day = $_POST["day"];
$year = $_POST["year"];
$bmonth = $_POST["bmonth"];
$bday = $_POST["bday"];
$byear = $_POST["byear"];
/*
$emonth = $_POST["emonth"];
$eday = $_POST["eday"];
$eyear = $_POST["eyear"];
*/
$phone = $_POST["phone"];
$aim = $_POST["aim"];
$mod = $_POST["mod"];
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
$query="INSERT INTO bio VALUES ('','$first','$middle','$last','$class','$gender','$email','$department','$job','$year/$month/$day','','$byear/$bmonth/$bday','$cleanphone','$aim','1','1')";
mysqlquery($dbnames,$query);
echo "You have successfully been entered into the staff system.<br>
<a href=\"/3m/\"> Click here to go back</a>";
?>