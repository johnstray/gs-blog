<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple Blog
 * Description: Manages a blog for you website
 * Version: 4.0.0
 * Author: John Stray
 * Author URI: https://www.johnstray.com/
 */
 
# Prepare the plugin
define('GSBLOG', basename(__FILE__, ".php"));             // Get correct ID for plugin
require_once(GSBLOG.'/common.php');                       // Include the common file

# Register the plugin
register_plugin(
  GSBLOG,                                                 // Plugin ID
  i18n_r(GSBLOG.'/PLUGIN_TITLE'),                         // Plugin Name
  GSBLOGVERSION,                                          // Plugin Version
  'John Stray',                                           // Plugin Author
  'https://www.johnstray.com/get-simple/plugins/blog/',   // Author's Website
  i18n_r(GSBLOG.'/PLUGIN_DESCRIPTION'),                   // Plugin Description
  'gsblog',                                               // Page Type - On which Admin tab to display.
  'gsblog_admin'                                          // Main Function (Administration)
);

# Add Actions
add_action('nav-tab','createNavTab',array('gsblog',GSBLOG, i18n_r(GSBLOG.'/BLOG_TAB_BUTTON'), 'manage'));         // Creates new Admin tab
add_action('gsblog-sidebar','createSideMenu',array(GSBLOG, i18n_r(GSBLOG.'/MANAGE_POSTS_BUTTON'),'manage'));      // Sidebar: Manage Posts
add_action('gsblog-sidebar','createSideMenu',array(GSBLOG, i18n_r(GSBLOG.'/CATEGORIES_BUTTON'),'categories'));    // Sidebar: Categories
add_action('gsblog-sidebar','createSideMenu',array(GSBLOG, i18n_r(GSBLOG.'/RSS_HEADER'),'auto_importer'));        // Sidebar: RSS Auto-Importer
add_action('gsblog-sidebar','createSideMenu',array(GSBLOG, i18n_r(GSBLOG.'/SETTINGS_BUTTON'),'settings'));        // Sidebar: Settings
add_action('gsblog-sidebar','createSideMenu',array(GSBLOG, i18n_r(GSBLOG.'/UPDATE_BUTTON'),'update'));            // Sidebar: Update Check
add_action('gsblog-sidebar','createSideMenu',array(GSBLOG, i18n_r(GSBLOG.'/HELP_BUTTON'),'help'));                // Sidebar: Help
add_action('index-pretemplate', 'gsblog_setTitle', array() );                                                     // Page title fix

# Initialize the plugin
gsblog_assets();
