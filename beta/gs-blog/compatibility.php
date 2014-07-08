<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple Blog
 * Description: Manages a blog for you website
 * 
 * File: compatibility.php
 * Maps old -> new functions names to provide backwards compatibility
 */
 
function blog_display_posts() {
  gsBlog_displayPosts();
}