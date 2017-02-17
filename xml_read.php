<?php
include("dbconfig.php");
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());  
 
// select the database  
$dbSelected = mysql_select_db($database) or die("Error connecting to db."); 
// First we need to determine the leaf nodes
$SQLL = "SELECT t1.account_id FROM accounts AS t1 LEFT JOIN accounts as t2 "
." ON t1.account_id = t2.parent_id WHERE t2.account_id IS NULL";
$result = mysql_query( $SQLL ) or die("Couldn t execute query.".mysql_error());
$leafnodes = array();
while($rw = mysql_fetch_array($result,MYSQL_ASSOC)) {
   $leafnodes[$rw[account_id]] = $rw[account_id];
}
 
// Recursive function that do the job
function display_node($parent, $level) {
   global $leafnodes;
   if($parent >0) {
      $wh = 'parent_id='.$parent;
   } else {
      $wh = 'ISNULL(parent_id)';
   }
   $SQL = "SELECT account_id, name, acc_num, debit, credit, balance, parent_id FROM accounts WHERE ".$wh;
   $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
   while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      echo "<row>";         
      echo "<cell>". $row[account_id]."</cell>";
      echo "<cell>". $row[name]."</cell>";
      echo "<cell>". $row[acc_num]."</cell>";
      echo "<cell>". $row[debit]."</cell>";
      echo "<cell>". $row[credit]."</cell>";
      echo "<cell>". $row[balance]."</cell>";
      echo "<cell>". $level."</cell>";
      if(!$row[parent_id]) $valp = 'NULL'; else $valp = $row[parent_id];  // parent field
      echo "<cell><![CDATA[".$valp."]]></cell>";
      if($row[account_id] == $leafnodes[$row[account_id]]) $leaf='true'; else $leaf = 'false';  // isLeaf comparation
      echo "<cell>".$leaf."</cell>"; // isLeaf field
      echo "<cell>false</cell>"; // expanded field
      echo "</row>";
        // recursion
      display_node((integer)$row[account_id],$level+1);
   }
}
 
if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
   header("Content-type: application/xhtml+xml;charset=utf-8");
} else {
   header("Content-type: text/xml;charset=utf-8");
}
$et = ">";
echo "<?xml version='1.0' encoding='utf-8'?$et\n";
echo "<rows>";
echo "<page>1</page>";
echo "<total>1</total>";
echo "<records>1</records>";
// Here we call the function at root level
display_node('',0);
echo "</rows>";
?>
