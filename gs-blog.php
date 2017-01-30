<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple Blog
 * Description: A simple and easy to use blog/newsfeed for GetSimple
 * Version: 3.4.4
 * Authors: Mike Henken, John Stray, David Negrello
 *
 */

# Define some important stuff
define('BLOGFILE', basename(__FILE__, ".php"));
define('BLOGPLUGINNAME', i18n_r(BLOGFILE.'/PLUGIN_TITLE'));
define('BLOGEXTENDID','810');
define('BLOGVERSION','3.4.4');
require_once(BLOGFILE.'/inc/common.php');

# Setup languages and language settings.
i18n_merge(BLOGFILE) || i18n_merge(BLOGFILE, "en_US");
define('BLOGLANGUAGE',i18n_r(BLOGFILE.'/LANGUAGE_CODE')[0]);

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
add_action('blog-sidebar','createSideMenu',array(BLOGFILE, i18n_r(BLOGFILE.'/CUSTOM_FIELDS'),'custom_fields'));
add_action('blog-sidebar','createSideMenu',array(BLOGFILE, i18n_r(BLOGFILE.'/RSS_HEADER'),'auto_importer'));
add_action('blog-sidebar','createSideMenu',array(BLOGFILE, i18n_r(BLOGFILE.'/SETTINGS_BUTTON'),'settings'));
add_action('blog-sidebar','createSideMenu',array(BLOGFILE, i18n_r(BLOGFILE.'/UPDATE_BUTTON'),'update'));
add_action('blog-sidebar','createSideMenu',array(BLOGFILE, i18n_r(BLOGFILE.'/HELP_BUTTON'),'help'));
add_action('index-pretemplate', 'get_blog_title', array() );

# Register/Queue Styles
register_style (BLOGFILE.'_css', $SITEURL.'/plugins/'.BLOGFILE.'/css/admin_styles.css', '1.0', 'screen');
register_style ('font_awesome', '//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', '4.2.0', 'all');
queue_style (BLOGFILE.'_css',GSBACK);
queue_style ('font_awesome', GSBACK );

# Register/Queue Scripts
register_script('pluginManagementFA', $SITEURL.'plugins/'.BLOGFILE.'/js/pluginManagementFA.js', '1.0', TRUE);
register_script('table_paging', $SITEURL.'plugins/'.BLOGFILE.'/js/paging.js', '1.0', FALSE);
queue_script('pluginManagementFA', GSBACK);
queue_script('table_paging', GSBACK);
