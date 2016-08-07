<?php 
require_once('inc/common.php');
i18n_merge(BLOGFILE) || i18n_merge(BLOGFILE, "en_US");
define('BLOGLANGUAGE',i18n_r(BLOGFILE.'/LANGUAGE_CODE')[0]);

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