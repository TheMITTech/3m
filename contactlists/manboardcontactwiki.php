<?
include '../config.php';
header("Content-type: text/plain");
echo "{| border=\"1\"\n! Name !! Department !! Position !! E-mail !! Phone !! AIM\n";
$query="SELECT first, middle, last, dept, position, email, phone, aim FROM bio WHERE active=1 ORDER BY dept, position, last";
$result=mysqlquery($dbnames,$query);
$num=mysql_numrows($result);
for ($i=0; $i < $num; $i++) {
	$first=mysql_result($result,$i,"first");
	$middle=mysql_result($result,$i,"middle");
	$last=mysql_result($result,$i,"last");
	$dept=mysql_result($result,$i,"dept");
	$position=mysql_result($result,$i,"position");
	$email=mysql_result($result,$i,"email");
	$phone=mysql_result($result,$i,"phone");
	if ($phone) {
		$phone="(" . substr($phone,0,3) . ") " . substr($phone,3,3) . "-" . substr($phone,6);
	}
	$aim=mysql_result($result,$i,"aim");
	if ((substr($position,0,9)!="Associate")&&(substr($position,0,6)!="Senior")&&((substr($position,-7,7)=="Manager")||(substr($position,-6,6)=="Editor")||($position=="Editor in Chief")||($position=="Chairman")||($position=="Director")||($dept=="Advisory Board"))){
		echo "|-\n";
		echo "| [[$first $middle $last]] || [[$dept]] || $position || [mailto:$email $email] || $phone || $aim\n";
		}
	}
echo "|}"
?>
