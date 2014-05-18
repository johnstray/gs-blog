<?php

/* Include admin/inc/common.php if it is not loaded
 * - Check if global $rootPath var is set. If not set root path from actual file location. Include admin/inc/common.php if not loaded. 
 *
 */
$rootPath = $_SERVER['DOCUMENT_ROOT'];
if(!function_exists('getXML'))
{
    require_once($rootPath.'/admin/inc/common.php');
    function add_action(){}
    function add_filter(){}
}

/* Define Constants
 * 
 */
if(!defined(BLOGFILE)){define('BLOGFILE', 'gs-blog')};
define('BLOGPLUGINFOLDER', GSPLUGINPATH.BLOGFILE.'/');
define('BLOGPLUGINID', BLOGFILE);
define('BLOGSETTINGS', GSDATAOTHERPATH  . 'blog_settings.xml');
define('BLOGCATEGORYFILE', GSDATAOTHERPATH  . 'blog_categories.xml');
define('BLOGRSSFILE', GSDATAOTHERPATH  . 'blog_rss.xml');
define('BLOGPOSTSFOLDER', GSDATAPATH.'blog/');
define('BLOGCACHEFILE', GSDATAOTHERPATH  . 'blog_cache.xml');
define('BLOGCUSTOMFIELDS', GSDATAOTHERPATH  . 'blog_custom_fields.xml');
define('BLOGCUSTOMFIELDSFILE', 'blog_custom_fields.xml');

/* Include all the primary class files
 *
 */
$primaryClassFiles = glob(BLOGPLUGINFOLDER.'class/primary/*.php');
foreach($primaryClassFiles as $primaryClassFile)
{
	require_once($primaryClassFile);
}

/* Include all secondary class files
 *
 */
$classFiles = glob(BLOGPLUGINFOLDER.'class/*.php');
foreach($classFiles as $classFile)
{
	require_once($classFile);
}

/* Include all inc files in the inc folder 
 *
 */
$incFiles = glob(BLOGPLUGINFOLDER.'inc/*.php');
foreach($incFiles as $incFile)
{
	require_once($incFile);
}

/* Add Hooks & Filters. Register Scripts & Styles
 *
*/
# add_filter('content', 'blog_display_posts');
add_action('index-pretemplate', 'blog_display_posts');
add_action('theme-header', 'includeRssFeed');
add_action('index-pretemplate', 'set_post_description');
add_action('common', 'checkPermissions');

global $blogSettings;
if($blogSettings["sharethis"] == 'Y') 
{
	add_action('theme-header', 'shareThisToolHeader');
}

global $SITEURL;
if(function_exists('register_script'))
{
	if(isset($_GET['id']) && $_GET['id'] == BLOGPLUGINID)
	{
	    register_script(BLOGPLUGINNAME.'_js', $SITEURL.'/plugins/'.BLOGPLUGINID.'/js/admin_js.js', '1.0', TRUE);
	    register_style(BLOGPLUGINNAME.'_css', $SITEURL.'/plugins/'.BLOGPLUGINID.'/css/admin_styles.css', '1.0', 'screen');
	    
	    register_script('codemirror_js', $SITEURL.'plugins/blog/js/codemirror/lib/codemirror.js', '1.0', FALSE);
		register_script('codemirror_javascript', $SITEURL.'/plugins/'.BLOGPLUGINID.'/js/codemirror/mode/javascript/javascript.js', '1.0', FALSE);
		register_script('codemirror_php', $SITEURL.'/plugins/'.BLOGPLUGINID.'/js/codemirror/mode/php/php.js', '1.0',  FALSE);
		register_script('codemirror_css_hl', $SITEURL.'/plugins/'.BLOGPLUGINID.'/js/codemirror/mode/css/css.js', '1.0',  FALSE);
		register_script('codemirror_clike', $SITEURL.'/plugins/'.BLOGPLUGINID.'/js/codemirror/mode/clike/clike.js', '1.0',  FALSE);
		register_script('codemirror_xml_hl', $SITEURL.'/plugins/'.BLOGPLUGINID.'/js/codemirror/mode/xml/xml.js', '1.0',  FALSE);

		register_style('codemirror_css', $SITEURL.'/plugins/'.BLOGPLUGINID.'/js/codemirror/lib/codemirror.css', GSVERSION, 'screen');

	    queue_script(BLOGPLUGINNAME.'_js', GSBACK);
	    queue_style(BLOGPLUGINNAME.'_css', GSBACK);  
		queue_script('codemirror_js',GSBACK); 
		queue_script('codemirror_javascript',GSBACK); 
		queue_script('codemirror_php',GSBACK); 
		queue_script('codemirror_css',GSBACK); 
		queue_script('codemirror_clike',GSBACK); 
		queue_script('codemirror_xml_hl',GSBACK); 
		queue_script('codemirror_css_hl',GSBACK); 

		queue_style('codemirror_css',GSBACK); 
	}
}
/* Cross Compatibility */ 
else
{
	if(isset($_GET['id']) && $_GET['id'] == BLOGPLUGINID)
	{
	    add_action('header', 'addStyleP', array($SITEURL.'/plugins/'.BLOGPLUGINID.'/css/admin_styles.css'));
	    add_action('header', 'addScriptP', array($SITEURL.'/plugins/'.BLOGPLUGINID.'/js/admin_js.js'));

	    add_action('header', 'addStyleP', array($SITEURL.'/plugins/'.BLOGPLUGINID.'/js/codemirror/lib/codemirror.css'));
	    add_action('header', 'addScriptP', array($SITEURL.'plugins/blog/js/codemirror/lib/codemirror.js'));
	    add_action('header', 'addScriptP', array($SITEURL.'/plugins/'.BLOGPLUGINID.'/js/codemirror/mode/javascript/javascript.js'));
	    add_action('header', 'addScriptP', array($SITEURL.'/plugins/'.BLOGPLUGINID.'/js/codemirror/mode/php/php.js'));
	    add_action('header', 'addScriptP', array($SITEURL.'/plugins/'.BLOGPLUGINID.'/js/codemirror/mode/css/css.js'));
	    add_action('header', 'addScriptP', array($SITEURL.'/plugins/'.BLOGPLUGINID.'/js/codemirror/mode/clike/clike.js'));
	    add_action('header', 'addScriptP', array($SITEURL.'/plugins/'.BLOGPLUGINID.'/js/codemirror/mode/xml/xml.js'));
	   }

    function addStyleP($stylesheet)
    {
        $css = '<link href="'.$stylesheet.'" rel="stylesheet" type="text/css" />';
        echo $css;
    }
    
    function addScriptP($script)
    {
        $script = '<script type="text/javascript" src="'.$script.'"></script>';
        echo $script;
    }
}



/** 
* Show admin plugin navigation bar
* 
* @return void echos
*/  
function showAdminNav()
{
	global $blogUserPermissions;
	?>
	<div style="width:100%;margin:0 -15px -15px -10px;padding:0px;">
		<h3  class="floated"><?php i18n(BLOGFILE.'/PLUGIN_TITLE'); ?></h3>
		<div class="edit-nav clearfix">
			<p>
				<?php if($blogUserPermissions['bloghelp'] == true) { ?>
					<a href="load.php?id=blog&help" <?php echo (isset($_GET['help']) ? 'class="current"' : false); ?>><?php i18n(BLOGFILE.'/HELP'); ?></a>
				<?php } ?>
				<?php if($blogUserPermissions['blogsettings'] == true) { ?>
					<a href="load.php?id=blog&settings" <?php echo (isset($_GET['settings']) ? 'class="current"' : false); ?>><?php i18n(BLOGFILE.'/SETTINGS'); ?></a>
				<?php } ?>
				<?php if($blogUserPermissions['blogcustomfields'] == true) { ?>
					<a href="load.php?id=blog&custom_fields" <?php echo (isset($_GET['custom_fields']) ? 'class="current"' : false); ?>><?php i18n(BLOGFILE.'/CUSTOM_FIELDS'); ?></a>
				<?php } ?>
				<?php if($blogUserPermissions['blogrssimporter'] == true) { ?>
					<a href="load.php?id=blog&auto_importer" <?php echo (isset($_GET['auto_importer']) ? 'class="current"' : false); ?>><?php i18n(BLOGFILE.'/RSS_FEEDS'); ?></a>
				<?php } ?>
				<?php if( $blogUserPermissions['blogcategories'] == true) { ?>
					<a href="load.php?id=blog&categories" <?php echo (isset($_GET['categories']) ? 'class="current"' : false); ?>><?php i18n(BLOGFILE.'/CATEGORIES'); ?></a>
				<?php } ?>
				<?php if($blogUserPermissions['blogcreatepost'] == true) { ?>
					<a href="load.php?id=blog&create_post" <?php echo (isset($_GET['create_post']) ? 'class="current"' : false); ?>><?php i18n(BLOGFILE.'/CREATE_POST'); ?></a>
				<?php } ?>
					<a href="load.php?id=blog&manage" <?php echo (isset($_GET['manage']) ? 'class="current"' : false); ?>><?php i18n(BLOGFILE.'/MANAGE_POSTS'); ?></a>
			</p>
		</div>
	</div>
	</div>
	<div class="main" style="margin-top:-10px;">
	<?php
}

/** 
* Format a date
* 
* @param $date string The date to format 
* @return void
*/  
function formatPostDate($date)
{
	$Blog = new Blog;
	return $Blog->get_locale_date(strtotime($date), '%b %e, %Y');
}

/** 
* Include RSS Feed (Link goes is included in header of front end pages)
* 
* @return void
*/  
function includeRssFeed()
{
	global $SITEURL;
	$locationOfFeed = $SITEURL."rss.rss";
	$blog = new Blog;	
	$blogTitle = htmlspecialchars($blog->getSettingsData("rsstitle"));
	echo '<link href="'.$locationOfFeed.'" rel="alternate" type="application/rss+xml" title="'.$blogTitle.'">';
}

function checkPermissions()
{
	if(function_exists('add_mu_permission'))
	{
		add_mu_permission('blogsettings', 'Blog Settings');
		add_mu_permission('blogeditpost', 'Blog Edit Post');
		add_mu_permission('blogcreatepost', 'Blog Create Post');
		add_mu_permission('blogrssimporter', 'Blog RSS Importer');
		add_mu_permission('blogcategories', 'Blog Categories');
		add_mu_permission('bloghelp', 'Blog Help');
		add_mu_permission('blogcustomfields', 'Blog Custom Fields');
		add_mu_permission('blogdeletepost', 'Blog Delete Post');
	}
	if(!function_exists('check_user_permission'))
	{
		function check_user_permission()
		{
			return true;
		}
	}
	if(!function_exists('check_user_permissions'))
	{
		function check_user_permissions()
		{
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

/** 
* Get Blog Permissions
* Gets permissions for areas of admin panel for blog plugin - These are defined using the Multi User Plugin
* 
* @return void
*/  
function getBlogUserPermissions()
{
	global $blogUserPermissions;
	$current_user = get_cookie('GS_ADMIN_USERNAME');
	$blogUserPermissions = check_user_permissions($current_user);
}

?>
