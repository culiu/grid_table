<?php
require_once("dbconfig.php");

$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die ("connection Error: ". mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

$id = $_POST['id'];
$date = $_POST['invdate'];
$amount = $_POST['amount'];
$tax = $_POST['tax'];
//$total = $_POST['total'];
$email = $_POST['email'];
$note = $_POST['note'];


if ($_POST['oper'] == 'edit') {
  mysql_query("UPDATE invheader SET invdate='$date',amount=$amount,tax=$tax,email='$email',note='$note' where invid=$id") or die(mysql_error());
}
else if ($_POST['oper'] == 'add') {
  mysql_query("INSERT into invheader(invdate, amount, tax, email, note) VALUES ('$date', '$amount', '$tax', '$email', '$note')") or die(mysql_error());
}
else if ($_POST['oper'] == 'del') {
    $id_array = explode(',', $id);
    $sz = count($id_array);
    $a = "(";
    for ($i=0; $i<$sz; $i++) {
      $a .= $id_array[$i];
      if ($i < $sz-1) {
        $a .= ","; 
      }   
    }
    $a .= ")";
    
    mysql_query("DELETE FROM invheader WHERE invid in $a") or die(mysql_error());
}
mysql_close();
?>
