<?php if(basename($_SERVER["SCRIPT_FILENAME"]) != "rss.php"){
    if(!defined('IN_GS')){die('You cannot load this file directly!');}} // Security Check
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
 * Add Hooks & Filters.
 */

# Hooks & Filters
add_action('index-pretemplate', 'blog_display_posts');   // Displays posts on front end
add_action('theme-header', 'includeRssFeed');            // Add RSS link to site header
add_action('index-pretemplate', 'set_post_description'); // Place excerpt into meta description

/**-------------------------------------------------------------------------------------------------
 * formatPostDate($date)
 * Format a date - Left here for backwards compatibility with older code.
 * 
 * @param $date (string) The date to format 
 * @return void (void)
 */  
function formatPostDate($date) {
    $Blog = new Blog;
    return $Blog->get_locale_date(strtotime($date), i18n_r(BLOGFILE.'/DATE_DISPLAY'));
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
 * display_message()
 * Displays a Success/Error message only on the backend
 * 
 * @return void
 */
function display_message($message = '???', $type = 'info', $close = false) {
    if(is_frontend() == false) {
        $removeit = ($close ? ".removeit()" : "");
        $type = ucfirst($type);
        if($close == false) {
            $message = $message . ' <a href="#" onclick="clearNotify();" style="float:right;">'.i18n_r(BLOGFILE.'/CLOSE').'</a>';
        }
        echo "<script id=\"blogMsg".$type."\">notify".$type."('".$message."').popit()".$removeit.";</script>";
        echo "<div class=\"blogMsg".$type."\" style=\"display:none;\">".$message."</div>";
    }
}
