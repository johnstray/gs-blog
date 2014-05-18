<?php
# Define some important stuff
$thisfile = basename(__FILE__, ".php");
define('BLOGFILE', $thisfile);
define('BLOGPLUGINNAME', i18n_r(BLOGFILE.'/PLUGIN_TITLE'));
define('BLOGEXTENDID','810');
define('BLOGVERSION','3.1.3');
require_once($thisfile.'/inc/common.php');

# add in this plugin's language file
if(file_exists(BLOGSETTINGS)) {
	$settings_lang = getXML(BLOGSETTINGS);
	$GSBLOGLANG = $settings_lang->lang;
} else {
	$GSBLOGLANG = "en_US";
}
i18n_merge($thisfile) || i18n_merge($GSBLOGLANG);

# register plugin
register_plugin(
	$thisfile, // ID of plugin, should be filename minus php
	i18n_r(BLOGFILE.'/PLUGIN_TITLE'), 	
	BLOGVERSION,
	'JohnStray.com',
	'https://www.johnstray.com/get-simple/plugins/blog/', 
	i18n_r(BLOGFILE.'/PLUGIN_DESC'),
	'blog',
	'blog_admin_controller'  
);

# Tab/Sidebar Actions
/* Disabling the new settings button until version 3.2
   require_once($thisfile.'/settingsButton.php'); */
$blog_settings_button = 'Settings'; // A plain old title for now.

add_action('nav-tab','createNavTab',array('blog',$thisfile,'Blog', 'manage'));
add_action('blog-sidebar','createSideMenu',array($thisfile, 'View Posts','manage'));
add_action('blog-sidebar','createSideMenu',array($thisfile, 'Create Post','create_post'));
add_action('blog-sidebar','createSideMenu',array($thisfile, 'Categories','categories'));
add_action('blog-sidebar','createSideMenu',array($thisfile, 'RSS Feeds','auto_importer'));
add_action('blog-sidebar','createSideMenu',array($thisfile, 'Custom Fields','custom_fields'));
add_action('blog-sidebar','createSideMenu',array($thisfile, $blog_settings_button,'settings'));
add_action('blog-sidebar','createSideMenu',array($thisfile, 'Update Check','update'));
add_action('blog-sidebar','createSideMenu',array($thisfile, 'Help','help'));
add_action('index-pretemplate', 'set_blog_title', array() );
