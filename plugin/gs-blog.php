<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple Blog
 * Description: A simple and easy to use blog/newsfeed for GetSimple
 * Version: 3.3.0
 * Author: John Stray
 * Author URI: https://www.johnstray.com/
 */

# Define some important stuff
define('BLOGFILE', basename(__FILE__, ".php"));
define('BLOGPLUGINNAME', i18n_r(BLOGFILE.'/PLUGIN_TITLE'));
define('BLOGEXTENDID','810');
define('BLOGVERSION','3.3.0');
require_once(BLOGFILE.'/inc/common.php');

# add in this plugin's language file
if(file_exists(BLOGSETTINGS)) {
	$settings_lang = getXML(BLOGSETTINGS);
	$GSBLOGLANG = $settings_lang->lang;
} else {
	$GSBLOGLANG = "en_US";
}
i18n_merge(BLOGFILE) || i18n_merge(BLOGFILE, "en_US");

# register plugin
register_plugin(
	BLOGFILE, // ID of plugin, should be filename minus php
	i18n_r(BLOGFILE.'/PLUGIN_TITLE'), 	
	BLOGVERSION,
	'John Stray',
	'https://www.johnstray.id.au/get-simple/plug-ins/gs-blog-3/', 
	i18n_r(BLOGFILE.'/PLUGIN_DESC'),
	'blog',
	'blog_admin_controller'  
);

# Tab/Sidebar Actions
add_action('nav-tab','createNavTab',array('blog',BLOGFILE, i18n_r(BLOGFILE.'/BLOG_TAB_BUTTON'), 'manage'));
add_action('blog-sidebar','createSideMenu',array(BLOGFILE, i18n_r(BLOGFILE.'/MANAGE_POSTS_BUTTON'),'manage'));
add_action('blog-sidebar','createSideMenu',array(BLOGFILE, i18n_r(BLOGFILE.'/CATEGORIES_BUTTON'),'categories'));
add_action('blog-sidebar','createSideMenu',array(BLOGFILE, i18n_r(BLOGFILE.'/RSS_HEADER'),'auto_importer'));
add_action('blog-sidebar','createSideMenu',array(BLOGFILE, i18n_r(BLOGFILE.'/SETTINGS_BUTTON'),'settings'));
add_action('blog-sidebar','createSideMenu',array(BLOGFILE, i18n_r(BLOGFILE.'/UPDATE_BUTTON'),'update'));
add_action('blog-sidebar','createSideMenu',array(BLOGFILE, i18n_r(BLOGFILE.'/HELP_BUTTON'),'help'));
add_action('index-pretemplate', 'set_blog_title', array() );

# Register/Queue Styles
register_style (BLOGFILE.'_css', $SITEURL.'/plugins/'.BLOGFILE.'/css/admin_styles.css', '1.0', 'screen');
register_style ('font_awesome', '//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', '4.2.0', 'all');
queue_style (BLOGFILE.'_css',GSBACK);
queue_style ('font_awesome', GSBACK );