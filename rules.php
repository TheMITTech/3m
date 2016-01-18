<html>
  <head>
    <title>The Tech -- Email List Rules</title>
  <?php
    require_once dirname(__FILE__).'/common.php';
  ?>
  </head>
  <body>
    <h1>Email Rules</h1>
    <table border="5">
    <a href="./">Return to 3m</a>
   <tr style="font-weight:bold;"><td>Department</td><td>Position</td><td>Add-list</td><td>Notification List</td><td></td></tr>
    <?php
       $fields = array("ruleid", "dept", "position", "addlist", "notificationlist");
       $sql = "SELECT " . join(", ", $fields) . " FROM emailrules";
       $res = $mdb2->query($sql);
       foreach($fields as $field) {
         $res->bindColumn($field, $$field);
       }    
       if(PEAR::isError($res)) {
         error_log($res->getDebugInfo());
	 fatal("Could not get information for $ruleid: " . $res->getMessage());
       }
       while ($row = $res->fetchRow()) {
        printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td><a href='./createrule.php?ruleid=%s'>Edit</a></td></tr>", $dept, $position, $addlist, $notificationlist, $ruleid);
       }
    ?>

    </table>
  </body>
</html>
