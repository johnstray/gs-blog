<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple Blog
 * Description: Manages a blog for you website
 * 
 * File: common.php
 * Provides common functions for the plugin
 */

# Define Constants
define('GSBLOGVERSION','4.0.0');                            // Plugin Version
define('GSBLOGPLUGINURL',$SITEURL.'/plugins/'.GSBLOG.'/');  // Plugin Folder URL
define('GSBLOGPLUGINFOLDER',GSPLUGINPATH.GSBLOG.'/');       // Plugin Folder

# Include Class files
$num_files = 0; $num_load = 0;
$classFiles = glob(GSBLOGPLUGINFOLDER.'*.class.php'); // Look for any class files
if ( empty( $classFiles ) ) { // No files found?
  gsblog_debug('Where are my classes?'); // Report to the Debug Log
} else { // Files were found
  $num_files = count($classFiles); // How many files were found?
  foreach ( $classFiles as $classFile ) { // For each of the files
    if( require_once( $classFile ) ) { // Include the file if possible
      $num_load++; // Count the number of files loaded.
    }
  }
  // Debugging: Compare number of files found to number loaded.
  if ( $num_files != $num_load ) {
    gsblog_debug($num_files.' Class files found. '.$num_load.' loaded.');
  }
}

# Include Function files
$num_files = 0; $num_load = 0;
$funcFiles = glob(GSBLOGPLUGINFOLDER.'*.inc.php'); // Look for any function files
if ( empty( $funcFiles ) ) { // No files found?
  gsblog_debug('Where are my functions?'); // Report to the Debug Log
} else { // Files were found
  $num_files = count($funcFiles); // How many files were found?
  foreach ( $funcFiles as $funcFile ) { // For each of the files
    if( require_once( $funcFile ) ) { // Include the file if possible
      $num_load++; // Count the number of files loaded.
    }
  }
  // Debugging: Compare number of files found to number loaded.
  if ( $num_files != $num_load ) {
    gsblog_debug($num_files.' Class files found. '.$num_load.' loaded.');
  }
}
/**
 * gsblog_assets()
 * Function to register and queue styles and scripts
 * 
 * @return void
 */
function gsblog_assets() {

  # Register / Queue CSS Styles
  $cssFiles = glob(GSBLOGPLUGINFOLDER.'assets/css/*.css'); // Look for any css files
  if ( empty( $cssFiles ) ) { // No files found?
    gsblog_debug('Where are my StyleSheets?'); // Report to the Debug Log
  } else { // Files were found
    foreach ( $cssFiles as $cssFile ) { // For each of the files
      register_style(GSBLOG.'_'.$cssFile, GSBLOGPLUGINURL.'assets/css/'.$cssFile, '1.0', 'screen'); // Register the StyleSheet
      queue_style(GSBLOG.'_'.$cssFile, GSBOTH); // Queue the StyleSheet
    }
  }
  
  # Register / Queue Scripts
  $jsFiles = glob(GSBLOGPLUGINFOLDER.'assets/js/*.js'); // Look for any css files
  if ( empty( $jsFiles ) ) { // No files found?
    gsblog_debug('Where are my JavaScripts?'); // Report to the Debug Log
  } else { // Files were found
    foreach ( $jsFiles as $jsFile ) { // For each of the files
      register_script(GSBLOG.'_'.$jsFile, GSBLOGPLUGINURL.'assets/js/'.$jsFile, '1.0', 'screen'); // Register the Script
      queue_script(GSBLOG.'_'.$jsFile, GSBOTH); // Queue the Script
    }
  }
}

/**
 * gsblog_debug($msg)
 * Logs information in the debug log.
 *
 * @return void
 */
function gsblog_debug($msg) {
  return debugLog('[GS Blog]: '.$msg);
}
