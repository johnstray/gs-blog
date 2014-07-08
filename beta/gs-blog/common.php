<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple Blog
 * Description: Manages a blog for you website
 * 
 * File: common.php
 * Provides common functions for the plugin
 */

# ===== DEFINE CONSTANTS ===== #

  define('GSBLOGVERSION','4.0.0');                                  // Plugin Version
  define('DS',DIRECTORY_SEPARATOR);
  
  define('GSBLOGPLUGINURL',$SITEURL.'/plugins/'.GSBLOG.'/');        // Plugin Folder URL
  define('GSBLOGPLUGINFOLDER',GSPLUGINPATH.GSBLOG.DS);              // Plugin Folder
  define('GSBLOGCLASSFOLDER',GSBLOGPLUGINFOLDER.'class'.DS);        // Class Files Folder
  define('GSBLOGLIBFOLDER',GSBLOGPLUGINFOLDER.'lib'.DS);            // Function Library folder
  define('GSBLOGADMINCLASS',GSBLOGCLASSFOLDER.'admin.class.php');   // Backend Class File
  define('GSBLOGFRONTCLASS',GSBLOGCLASSFOLDER.'front.class.php');   // Frontend Class File
  
  define('GSBLOGDATAPATH',GSDATAPATH.'blog'.DS);                    // Data directory
  define('GSBLOGPOSTSFOLDER',GSBLOGDATAPATH.'posts'.DS);            // Blog Posts directory
  define('GSBLOGCATEGORIESFOLDER',GSBLOGDATAPATH.'categories'.DS);  // Categories Directory
  define('GSBLOGSETTINGSFILE',GSBLOGDATAPATH.'settings.xml');       // Settings file

# ===== DEBUG LOGGING ===== #

  function gsBlog_debug($msg) {
    return debugLog('[GS Blog]: '.$msg);
  }
  
  function gsBlog_umsg($msg, $type='OK') {
    # Do something...
    
    gsBlog_debug('('.$type.') '.$msg);
  }

?>