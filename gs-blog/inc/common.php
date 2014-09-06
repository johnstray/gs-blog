<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * @file: common.php
 * @package: GetSimple Blog [plugin]
 * @action: This file contains definitions, functions and inclusions required throughout the plugin.
 * @author: John Stray [https://www.johnstray.id.au/]
 */

/**-------------------------------------------------------------------------------------------------
 * Include admin/inc/common.php if it is not loaded
 * Check if global $rootPath var is set. If not set root path from actual file location.
 * Include admin/inc/common.php if not loaded. 
 * @TODO: Figure out what this is for.
 */
$rootPath = $_SERVER['DOCUMENT_ROOT'];
if(!function_exists('getXML')) {
  require_once($rootPath.'/admin/inc/common.php');
  function add_action(){}
  function add_filter(){}
}

/**-------------------------------------------------------------------------------------------------
 * Define Constants
 */
if(!defined('BLOGFILE')){define('BLOGFILE', 'gs-blog');}                 // Plugin ID
define('BLOGPLUGINFOLDER', GSPLUGINPATH.BLOGFILE.'/');                   // Plugin's base folder
define('BLOGPLUGINID', BLOGFILE);                                        // Backwards compatibility
define('BLOGSETTINGS', GSDATAOTHERPATH  . 'blog_settings.xml');          // The settings file
define('BLOGCATEGORYFILE', GSDATAOTHERPATH  . 'blog_categories.xml');    // The categories list
define('BLOGRSSFILE', GSDATAOTHERPATH  . 'blog_rss.xml');                // Stored RSS feed
define('BLOGPOSTSFOLDER', GSDATAPATH.'blog/');                           // Where posts are stored
define('BLOGCACHEFILE', GSDATAOTHERPATH  . 'blog_cache.xml');            // Cache of all posts
define('BLOGCUSTOMFIELDS', GSDATAOTHERPATH  . 'blog_custom_fields.xml'); // List of custom fields
define('BLOGCUSTOMFIELDSFILE', 'blog_custom_fields.xml');                // Backwards Compatibility?

/**-------------------------------------------------------------------------------------------------
 * Include all the class files
 */

# The core class needs to be included before any others
require_once(BLOGPLUGINFOLDER.'class/Blog.php');

# Include the remaining class files
$classFiles = glob(BLOGPLUGINFOLDER.'class/*.php');
foreach($classFiles as $classFile) {
  if($classFile != BLOGPLUGINFOLDER.'class/Blog.php') {
	  require_once($classFile);
  }
}

/**-------------------------------------------------------------------------------------------------
 * Include all inc files in the inc folder 
 */
$incFiles = glob(BLOGPLUGINFOLDER.'inc/*.php');
foreach($incFiles as $incFile) {
	require_once($incFile);
}

/**-------------------------------------------------------------------------------------------------
 * Add Hooks & Filters. Register Scripts & Styles
 */

# Hooks & Filters
add_action('index-pretemplate', 'blog_display_posts');   // Displays posts on front end
add_action('theme-header', 'includeRssFeed');            // Add RSS link to site header
add_action('index-pretemplate', 'set_post_description'); // Place excerpt into meta description
add_action('common', 'checkPermissions');                // Check what permission the user has

# Scripts & Styles
register_script('pluginManagementFA', $SITEURL.'plugins/'.BLOGFILE.'/js/pluginManagementFA.js', '1.0', TRUE);
queue_script('pluginManagementFA', GSBACK);

/**-------------------------------------------------------------------------------------------------
 * formatPostDate($date)
 * Format a date - Left here for backwards compatibility with older code.
 * 
 * @param $date (string) The date to format 
 * @return void (void)
 */  
function formatPostDate($date) {
	$Blog = new Blog;
	return $Blog->get_locale_date(strtotime($date), '%b %e, %Y');
}

/**-------------------------------------------------------------------------------------------------
 * includeRSSFeed()
 * Include RSS Feed (Link is included in header of front end pages)
 * 
 * @return void
 */  
function includeRssFeed() {
	global $SITEURL; // Declare GLOBAL variables
	$locationOfFeed = $SITEURL."rss.rss"; // Define location of the RSS file.
	$blog = new Blog;	// Create instance of the BLOG class
	$blogTitle = htmlspecialchars($blog->getSettingsData("rsstitle")); // Get the RSS feed title
	echo '<link href="'.$locationOfFeed.'"
    rel="alternate" type="application/rss+xml" title="'.$blogTitle.'">'; // Echo out the <link>
}

/**-------------------------------------------------------------------------------------------------
 * checkPermissions()
 * Checks what permissions are set (if any) or sets them to allow all if none are found
 * 
 * @return bool (bool)
 * @return $blogUserPermissions (array) - Array of Allow/Deny permissions
 */
function checkPermissions() {
	
  # If possible, let the core know what permissions can be set here
  if(function_exists('add_mu_permission')) {
		add_mu_permission('blogsettings', 'Blog Settings');
		add_mu_permission('blogeditpost', 'Blog Edit Post');
		add_mu_permission('blogcreatepost', 'Blog Create Post');
		add_mu_permission('blogrssimporter', 'Blog RSS Importer');
		add_mu_permission('blogcategories', 'Blog Categories');
		add_mu_permission('bloghelp', 'Blog Help');
		add_mu_permission('blogcustomfields', 'Blog Custom Fields');
		add_mu_permission('blogdeletepost', 'Blog Delete Post');
	}
  
  # Creates check_user_permission function that returns Allow All if the core function is not available
	if(!function_exists('check_user_permission')) {
		function check_user_permission() {
			return true;
		}
	}
  
  # If unable to get a users permission, set them to allow all. 
	if(!function_exists('check_user_permissions')) {
		function check_user_permissions() {
			$blogUserPermissions = array();
			$blogUserPermissions['blogsettings'] = true;
			$blogUserPermissions['blogeditpost'] = true;
			$blogUserPermissions['blogcreatepost'] = true;
			$blogUserPermissions['blogcategories'] = true;
			$blogUserPermissions['blogrssimporter'] = true;
			$blogUserPermissions['bloghelp'] = true;
			$blogUserPermissions['blogcustomfields'] = true;
			$blogUserPermissions['blogdeletepost'] = true;
			return $blogUserPermissions;
		}
	}
}

/**-------------------------------------------------------------------------------------------------
 * getBlogUserPermissions()
 * Gets permissions for areas of admin panel for blog plugin.
 * These are defined using the Multi User Plugin
 * 
 * @return void (void)
 */  
function getBlogUserPermissions() {
	global $blogUserPermissions;
	$current_user = get_cookie('GS_ADMIN_USERNAME');
	$blogUserPermissions = check_user_permissions($current_user);
}
