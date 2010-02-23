<?php
include 'config.php';
header('Content-type: text/plain; charset="UTF-16BE"');
header('Content-Disposition: attachment; filename=dynamicmarkmast.txt');
echo("\xfe\xff");
specialEcho("<UNICODE-MAC>\r");
// These define a bunch of variables that should be changed if the format of the mast changes
$nameSeperator=",";
$jobSeperator=";";
$sectionSeperator=".";
$quoteSymbol="\xe2\x80\x99"; // this is the UTF8 for the symbol. we are going to build huge magical UTF8 strings that include this symbol and later we are going to turn them into UTF16 along with all their little character friends

// If turning your output into UTF16 strings by brute force
// is wrong, I want to be right. Please, please, I want to be right.
// Anything but this. Free me from this UTF16 prison.
function specialEcho($string){
  echo iconv('UTF-8', 'UTF-16BE', $string); 
}

// Takes a year and returns the appropriately formated data
function CleanYear($year){
	global $quoteSymbol, $blankYear, $gradYear, $cmeYear;
	// Two digits if they've got a year
	if(($year!=$gradYear)&&($year!=$blankYear)&&($year!=$cmeYear)){
		$twoDigits=substr($year,-2,2);
		return(" $quoteSymbol$twoDigits");
	}
	// G if they're grad
	if ($year==$gradYear) {
		return(" G");
	} elseif ($year==$cmeYear) {
          return(" CME");
        }
	// Default is blank
	else{
		return("");
	}
}

// This was really written to handle middle names
// Calls cleanYear to format years properly
function CleanName($first, $middle, $last, $year){
	$year = CleanYear($year);
	if ($middle==""){
		return("$first $last$year");
	}
	else {
		return("$first $middle $last$year");
	}
}

// Queries the database and returns an array with the ordered mast
// zzzzzz is the table with the mast because it will always come last
function DeptOrder(){
	global $dbdepts;
	$result=mysqlquery($dbdepts, "SELECT positions FROM `zzzzzz` ORDER BY `id` ASC");
	$num=mysql_numrows($result);
	$resultarray=array();
	for($i=0;$i<$num;$i++){
		$dept = mysql_result($result,$i,"positions");
		array_push($resultarray, $dept);
	}
	return($resultarray);
}

// Takes a department and returns an array with all of the jobs associated with that department
// First item in the array is the title each department should have in print
function DeptPositions($dept){
	global $dbdepts;
	$result=mysqlquery($dbdepts, "SELECT positions FROM `$dept` ORDER BY `id` ASC");
	$num=mysql_numrows($result);
	$resultarray=array();
	for($i=0;$i<$num;$i++){
		$jobs = mysql_result($result,$i,"positions");
		array_push($resultarray, $jobs);
	}
	return($resultarray);
}

// Takes a department and position and returns an array containing everyone's name and year in appropriate order
// Calls cleanName to properly format the names
function StaffNames($dept, $position){
	global $dbnames;
	$result=mysqlquery($dbnames, "SELECT `first`,`middle`,`last`,`year` FROM bio WHERE active=1 AND dept='$dept' AND position='$position' ORDER BY year,last");
	$num=mysql_numrows($result);
	$resultarray=array();
	for($i=0;$i<$num;$i++){
		$first=mysql_result($result,$i,"first");
		$middle=mysql_result($result,$i,"middle");
		$last=mysql_result($result,$i,"last");
		$year=mysql_result($result,$i,"year");
		$person=CleanName($first, $middle, $last, $year);
		array_push($resultarray, $person);
	}
	return($resultarray);
}

// Calls DeptOrder, DeptPositions, and StaffNames in various loops to generate a series of nested arrays of the form [Print Title [Job [Person]]]
// If there is no one in that department or who holds that position, that respective branch of the hiearchy is left out
function arrayCompile(){
	$orderarray = DeptOrder(); //Gets the department order
	$mastArray=array();
	foreach($orderarray as $dept){ //Goes through each department
		$DeptJobs = DeptPositions($dept);
		$printTitle=array_shift($DeptJobs); //This takes how the department should be printed off the top of the stack
		$deptArray=array($printTitle); //And this places how it should be printed at the front of the array
		foreach($DeptJobs as $job){ //Goes throgh each position in a department
			$peopleArray = StaffNames($dept, $job); //Gets all the people in that position
			$staffSize=count($peopleArray);
			if($staffSize!=0){ //Only adds it to the array if they're people in that department
				if (($staffSize>1)&&($job!="Staff")){
					$job=$job."s"; //Plurality Handler
				}
				$jobArray = array($job,$peopleArray);
				array_push($deptArray,$jobArray);
			}
		}
		if(count($deptArray)>1){ //Only ads the department if there are jobs in that department
			array_push($mastArray,$deptArray);
		}
	}
	return($mastArray);
}

//This prints out each job with the appropriate enclosing tags
function jobEcho($job, $jobStyleOpen, $jobStyleClose, $personOpen, $personClose, $lastPersonOpen, $lastPersonClose){
	$printJob=array_shift($job); //Gets the printed title for the department from the array
	specialEcho("$jobStyleOpen$printJob$jobStyleClose"); //Prints it
	$peopleArray=array_shift($job); //Gets the array of staff
	$lastPerson=array_pop($peopleArray); //Takes the last person out of the array (They have special formatting like a semi-colon or period instead of a comma
	foreach($peopleArray as $person){
	  specialEcho("$personOpen$person$personClose"); //Normal Person
	}
	specialEcho("$lastPersonOpen$lastPerson$lastPersonClose"); //Last Person
}

$mastArray=arrayCompile(); //Instantiates the hiearchical mast array (Spelling = bad)
//This is where things get a bit sketchy in terms of hacking stuff in. Specifically the poping and shifting to get the executive board, adboard, and prod staff for this issue out of the regular que. It's because they have special formatting.
//Remove any departments that require special formatting from the queue here 
$executiveBoard=array_shift($mastArray);
array_shift($executiveBoard);//This takes off the blank print name
$prodStaffThisIssue=array_pop($mastArray);
$adBoard=array_pop($mastArray);
array_shift($adBoard); //This takes off the blank print name
foreach($executiveBoard as $job){
	jobEcho($job, "<ParaStyle:PROD-Misc\:MAST\:PROD-MastTop><cTypeface:Bold>", "<cTypeface:>\x0A", "", "\n", "", "\r"); //Echos ExecBoard; Uses the jobEcho with special formatting
}

foreach($mastArray as $dept){
	$printDept=array_shift($dept);
	specialEcho("<ParaStyle:PROD-Misc\:MAST\:PROD-MastDept>$printDept\r"); //Echos the department name
	$lastJob=array_pop($dept); //Last job will have special formatting (IE a period) so it's removed from the queue
	specialEcho("<ParaStyle:PROD-Misc\:MAST\:PROD-MastPeople>");
	foreach($dept as $job){
		jobEcho($job, "<cTypeface:Bold>", ":<cTypeface:> ", "", ", ", "", "; ");
	}
	jobEcho($lastJob, "<cTypeface:Bold>", ":<cTypeface:> ", "", ", ", "", ".\r"); //Last job is echoed here
}

foreach($adBoard as $job){
	$job[0]=substr($job[0], 0, -1); //Normally, adboard would get an "s" attached to the end; this suppresses it
	jobEcho($job, "<ParaStyle:PROD-Misc\:MAST\:PROD-MastDept>", "\r<ParaStyle:PROD-Misc\:MAST\:PROD-MastPeople>", "", ", ", "", ".\r"); //Special Adboard formatting
}

//This is effectively a normal department echo, except that it fixes the department title so that it's "Production Staff for This Issue" instead of just "Produciton Staff"
$printDept=array_shift($prodStaffThisIssue)." for This Issue";
specialEcho("<ParaStyle:PROD-Misc\:MAST\:PROD-MastDept>$printDept\r");
$lastJob=array_pop($prodStaffThisIssue);
specialEcho("<ParaStyle:PROD-Misc\:MAST\:PROD-MastPeople>");
foreach($prodStaffThisIssue as $job){
		jobEcho(array($job[0], array("@@")), "<cTypeface:Bold>", ":<cTypeface:> ", "", ", ", "", "; ");
}
jobEcho(array($lastJob[0], array("@@")), "<cTypeface:Bold>", ":<cTypeface:> ", "", ", ", "", ".\r");

//Echo's the copyright and whatever at the bottom. Makes sure the year is correct.
$currentyear=date("Y");
specialEcho("<ParaStyle:PROD-Misc\:MAST\:PROD-MastBottom><cTypeface:Italic>The Tech<cTypeface:> (ISSN 0148-9607) is published on Tuesdays and Fridays during the academic year (except during MIT vacations), Wednesdays during January, and monthly during the summer by The Tech, Room W20-483, 84 Massachusetts Avenue, Cambridge, Mass. 02139. Subscriptions are $50.00 per year (third class). <cTypeface:Bold><cCase:All Caps>Postmaster:<cCase:><cTypeface:> Please send all address changes to our mailing address: The Tech, P.O. Box 397029, Cambridge, Mass. 02139-7029. <cTypeface:Bold><cCase:All Caps>Telephone:<cCase:><cTypeface:> Editorial: (617) 253-1541. Business: (617) 258-8324. Facsimile: (617) 258-8226. <cTypeface:Italic>Advertising, subscription, and typesetting rates available.<cTypeface:> Entire contents <cTypeface:Bold Italic>\xc2\xa9 $currentyear The Tech<cTypeface:>. <cTypeface:Italic>Printed on recycled paper by Mass Web Printing Company.<cTypeface:>");
?>
