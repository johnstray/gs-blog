<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple Blog
 * Description: Manages a blog for you website
 * 
 * File: providers.inc.php
 * Provides information about Posting and Comment Providers
 */
 
function gsblog_providers($type) {
  
  // Commenting Providers
  if ( $type == 'comments' ) {
    return array (
      
      # Facebook
      'facebook' => array(
        'name' => 'Facebook',
        'markup' => '',
        'options' => ''
      ),
      
      # Google+
      'googleplus' => array(
        'name' => 'Google+',
        'markup' => '',
        'options' => ''
      ),
      
      # Twitter
      'twitter' => array(
        'name' => 'Twitter',
        'markup' => '',
        'options' => ''
      ),
    );
  
  // Posting Providers
  } elseif ( $type == 'posting' ) {
    return array (
      
      # Facebook
      'facebook' => array(
        'name' => 'Facebook',
        'url' => '',
        'options' => ''
      ),
      
      # Google+
      'googleplus' => array(
        'name' => 'Google+',
        'url' => '',
        'options' => ''
      ),
      
      # Twitter
      'twitter' => array(
        'name' => 'Twitter',
        'url' => '',
        'options' => ''
      ),
    );
  }
}