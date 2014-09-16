<?php 
if(!defined('IN_GS')){define('IN_GS',true;)}
require_once('inc/common.php');
$Blog = new Blog;
if(isset($_GET['filter']) && isset($_GET['value']))
{
	$filter = array();
	$filter['filter'] = $_GET['filter'];
	$filter['value'] = urldecode($_GET['value']);
}
else
{
	$filter = false;
}
echo $Blog->generateRSSFeed(false, $filter);
?>