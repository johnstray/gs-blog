<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple Blog
 * Description: Manages a blog for your website
 * 
 * File: admin.class.php
 * Provides functions for the Admin area
 */
 
class gsBlogAdmin
{
 /**
  * editPost($post_id)
  * Shows the WYSIWYG editor to create or edit a post
  * 
  * @param  (array)$post_id   : The post id to edit, NULL to create new
  * @return (void)void
  */
  public function editPost($post_id=null)
  {
    if (require_once(GSBLOGMAINCLASS)) { // Load blog.class.php if we can
    
      # Are we editing or creating?
      if ($post_id = NULL) { // Creating
        $post_data = NULL;
      } else { // Editing
        $post_data = $gsBlog->getPost($post_id); // Get data for the post we are editing
      }
      
      # Get the post editor
      if (file_exists(GSBLOGASSETSFOLDER.'html/postEditor.inc.php')) { // Does it exist?
        require_once(GSBLOGASSETSFOLDER.'html/postEditor.inc.php'); // Yes, let's require it.
      } else { // No...
        gsBlog_debug('editPost: FAIL - postEditor.inc.php ',i18n_r(GSBLOG.'/DOESNT_EXIST')); // Notify the debug
      }
      
    } else { // blog.class.php failed to load...
      gsBlog_debug('editPost: FAIL - Failed to load main class file');
    }
  }
  
 /**
  * listPosts()
  * Shows a list of posts in the Admin area
  * 
  * @return (void)void
  */
  public function listPosts()
  {
    
  }
      