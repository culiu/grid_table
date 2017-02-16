<?php
require_once 'jq-config.php';
// include the jqGrid Class
require_once 'php/jqGrid.php';
// include the driver class
require_once 'php/jqGridPdo.php';
// Connection to the server
$conn = new PDO(DB_DSN,DB_USER,DB_PASSWORD);
// Tell the db that we use utf-8
$conn->query("SET NAMES utf8");

// Create the jqGrid instance
$grid = new jqGridRender($conn);
// Write the SQL Query
$grid->SelectCommand = 'SELECT invid, invdate, amount, tax,total, note FROM invheader';
// Set the table to where you delete the data
$grid->table = 'invheader';
// Set output format to json
$grid->dataType = 'json';
// Let the grid create the model
$grid->setColModel();
// Set the url from where we obtain the data
$grid->setUrl('delete_multi.php');
// Set some grid options
$grid->setGridOptions(array(
    "rowNum"=>10,
    "multiselect"=>true,
    "rowList"=>array(10,20,30),
    "sortname"=>"invid"
));
// Enable navigator
$grid->navigator = true;
// Enable only deleting
$grid->setNavOptions('navigator', array("excel"=>false,"add"=>false,"edit"=>false,"del"=>true,"view"=>false, "search"=>false));
// Enjoy
$grid->renderGrid('#list','#pager',true, null, null, true,true);
$conn = null;
?>
