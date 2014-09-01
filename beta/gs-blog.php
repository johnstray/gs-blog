<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple Blog
 * Description: Manages a blog for you website
 * Version: 4.0.0
 * Author: John Stray
 * Author URI: https://www.johnstray.com/
 */
 
# ===== INIT ===== #

  define('GSBLOG', basename(__FILE__, ".php"));             // Get correct ID for plugin
  require_once(GSBLOG.'/common.php');                       // Include the common file

# ===== REGISTER PLUGIN ===== #

  register_plugin(
    GSBLOG,                                                 // Plugin ID
    i18n_r(GSBLOG.'/PLUGIN_TITLE'),                         // Plugin Name
    GSBLOGVERSION,                                          // Plugin Version
    'John Stray',                                           // Plugin Author
    'https://www.johnstray.com/get-simple/plugins/blog/',   // Author's Website
    i18n_r(GSBLOG.'/PLUGIN_DESCRIPTION'),                   // Plugin Description
    'gsblog',                                               // Page Type - On which Admin tab to display.
    'gsBlog_admin'                                          // Main Function (Administration)
  );

# ===== ADD COMMON ACTIONS ===== #

  add_action('nav-tab','createNavTab',array('gsblog',GSBLOG, i18n_r(GSBLOG.'/BLOG_TAB_BUTTON'), 'manage'));         // Creates new Admin tab
  add_action('gsblog-sidebar','createSideMenu',array(GSBLOG, i18n_r(GSBLOG.'/MANAGE_POSTS_BUTTON'),'manage'));      // Sidebar: Manage Posts
  add_action('gsblog-sidebar','createSideMenu',array(GSBLOG, i18n_r(GSBLOG.'/CATEGORIES_BUTTON'),'categories'));    // Sidebar: Categories
  add_action('gsblog-sidebar','createSideMenu',array(GSBLOG, i18n_r(GSBLOG.'/RSS_HEADER'),'auto_importer'));        // Sidebar: RSS Auto-Importer
  add_action('gsblog-sidebar','createSideMenu',array(GSBLOG, i18n_r(GSBLOG.'/SETTINGS_BUTTON'),'settings'));        // Sidebar: Settings
  add_action('gsblog-sidebar','createSideMenu',array(GSBLOG, i18n_r(GSBLOG.'/UPDATE_BUTTON'),'update'));            // Sidebar: Update Check
  add_action('gsblog-sidebar','createSideMenu',array(GSBLOG, i18n_r(GSBLOG.'/HELP_BUTTON'),'help'));                // Sidebar: Help
  add_action('index-pretemplate', 'gsblog_setTitle', array() );                                                     // Page title fix

# ===== SCRIPTS / STYLES ===== #

  # NOTE: The following 2 functions will automatically detect and
  #       prepare all scripts and styles found in the 'assets/js'
  #       and 'assets/css' folders. This allows for easy drop-in
  #       customization of the plugin.

  # Register / Queue CSS Styles
  $cssFiles = glob(GSBLOGASSETSFOLDER.'css/*.css'); // Look for any css files
  if ( empty( $cssFiles ) ) { // No files found?
    gsBlog_debug('Where are my StyleSheets?'); // Report to the Debug Log
  } else { // Files were found
    foreach ( $cssFiles as $cssFile ) { // For each of the files
      register_style(GSBLOG.'_'.$cssFile, GSBLOGPLUGINURL.'assets/css/'.$cssFile, GSBLOGVERSION, 'screen'); // Register the StyleSheet
      queue_style(GSBLOG.'_'.$cssFile, GSBOTH); // Queue the StyleSheet
    }
  }
  
  # Register / Queue Scripts
  $jsFiles = glob(GSBLOGASSETSFOLDER.'js/*.js'); // Look for any js files
  if ( empty( $jsFiles ) ) { // No files found?
    gsBlog_debug('Where are my JavaScripts?'); // Report to the Debug Log
  } else { // Files were found
    foreach ( $jsFiles as $jsFile ) { // For each of the files
      register_script(GSBLOG.'_'.$jsFile, GSBLOGPLUGINURL.'assets/js/'.$jsFile, GSBLOGVERSION, 'screen'); // Register the Script
      queue_script(GSBLOG.'_'.$jsFile, GSBOTH); // Queue the Script
    }
  }

# ===== ADMIN FUNCTIONS ===== #

  function gsBlog_admin() {
  
    if(require_once(GSBLOGMAINCLASS)) {
      gsBlog::__construct();
    } else {
      gsBlog_debug('Could not show user messages, admin.class.php failed to load.');
    }
  
    # Show messages to the user
    if(require_once(GSBLOGADMINCLASS)) {
      gsBlogAdmin::showUmsg();
    } else {
      gsBlog_debug('Could not show user messages, admin.class.php failed to load.');
    }
  
    # Show editor to create a new post
    if(isset($_GET['create_post']) && $blogUserPermissions['blogcreatepost'] == true) {
      if(require_once(GSBLOGADMINCLASS)) {
        gsBlogAdmin::editPost(NULL);
      } else {
        gsBlog_debug('Could not open editor to create post, admin.class.php failed to load.');
      }
    }
    
    # Show editor to edit an existing post
    if(isset($_GET['edit_post']) && $blogUserPermissions['blogeditpost'] == true) {
      if(require_once(GSBLOGADMINCLASS)) {
        gsBlogAdmin::editPost($_GET['edit_post']);
      } else {
        gsBlog_debug('Could not open editor to edit post, admin.class.php failed to load.');
      }
    }
    
  }
  
# ===== FRONTEND FUNCTIONS ===== #

  # NOTE: Backwards compatibility has been provided to ease the
  #       changeover from previous versions of GS Blog. All existing
  #       functions in your themes (should) work as expected.
  #       To prevent the possibility of this plugin interfering with
  #       other plugins or any other possible problems, it is recommended
  #       that you update your function calls in your themes, then remove
  #       the compatibility file found in this plugins folder.
  
  function gsBlog_displayPosts() {
    // Do Something
  }
  
  
  # Backwards Compatibility - Delete file if not required!
  if(file_exists(GSBLOGPLUGINFOLDER.'compatibility.php')) {
    require_once(GSBLOGPLUGINFOLDER.'compatibility.php');
  }