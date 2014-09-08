<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * @file: frontEndFunctions.php
 * @package: GetSimple Blog [plugin]
 * @action: This file contains all the functions required for displaying the blog.
 * @author: John Stray [https://www.johnstray.id.au/]
 */
 
 
/**-------------------------------------------------------------------------------------------------
 * blog_admin_controller()
 * This functions controls what to do and where to go throughout the admin areas.
 * 
 * @return void (void)
 */ 
function blog_admin_controller() {

	$Blog = new Blog;
	getBlogUserPermissions();
	GLOBAL $blogUserPermissions, $SITEURL;
  
	if(!isset($_GET['update'])) {
		$update = blog_version_check();
		if($update[0] == 'current') {$ucolor = '#308000';}
		elseif($update[0] == 'update') {$ucolor = '#FFA500';}
		elseif($update[0] == 'beta') {$ucolor = '#2B5CB3';}
		else{$ucolor = '#D94136';}
	} else {$ucolor = '#777777';}

	if(isset($_GET['edit_post']) && $blogUserPermissions['blogeditpost'] == true) {
		editPost($_GET['edit_post']);
	} elseif(isset($_GET['create_post']) && $blogUserPermissions['blogcreatepost'] == true) {
		editPost();
	} elseif(isset($_GET['categories']) && $blogUserPermissions['blogcategories'] == true) {
		if(isset($_GET['edit_category'])) {
			$add_category = $Blog->saveCategory($_POST['new_category']);
			if($add_category == true) {
				echo '<div class="updated">'.i18n_r(BLOGFILE.'/CATEGORY_ADDED').'</div>';
			} else {
				echo '<div class="error">'.i18n_r(BLOGFILE.'/CATEGORY_ERROR').'</div>';
			}
		}
		if(isset($_GET['delete_category'])) {
			$Blog->deleteCategory($_GET['delete_category']);
		}
    #edit_categories
		$category_file = getXML(BLOGCATEGORYFILE);
    require_once('html/category-management.php');
	} elseif(isset($_GET['auto_importer']) && $blogUserPermissions['blogrssimporter'] == true) {
		if(isset($_POST['post-rss'])) {
			$post_data = array();
			$post_data['name'] = $_POST['post-rss'];
			$post_data['category'] = $_POST['post-category'];
			$add_feed = $Blog->saveRSS($post_data);
			if($add_feed == true) {
				echo '<div class="updated">'.i18n_r(BLOGFILE.'/FEED_ADDED').'</div>';
			} else {
				echo '<div class="error">'.i18n_r(BLOGFILE.'/FEED_ERROR').'</div>';
			}
		} elseif(isset($_GET['delete_rss'])) {
			$delete_feed = $Blog->deleteRSS($_GET['delete_rss']);
			if($delete_feed == true) {
				echo '<div class="updated">'.i18n_r(BLOGFILE.'/FEED_DELETED').'</div>';
			} else {
				echo '<div class="error">'.i18n_r(BLOGFILE.'/FEED_DELETE_ERROR').'</div>';
			}
		}
    #edit_rss
		$rss_file = getXML(BLOGRSSFILE);
    require_once('html/feed-management.php');
	} elseif(isset($_GET['settings']) && $blogUserPermissions['blogsettings'] == true) {
		show_settings_admin();
	} elseif(isset($_GET['update']) && $blogUserPermissions['blogsettings'] == true) {
		show_update_admin();
	} elseif(isset($_GET['help']) && $blogUserPermissions['bloghelp'] == true) {
		require_once('html/help-admin.php');
	} elseif(isset($_GET['custom_fields']) && $blogUserPermissions['blogcustomfields'] == true) {
		$CustomFields = new customFields;
		if(isset($_POST['save_custom_fields'])) {
			$saveCustomFields = $CustomFields->saveCustomFields();
			if($saveCustomFields) {
				echo '<div class="updated">'.i18n_r(BLOGFILE.'/EDIT_OK').'</div>';
			}
		}
		show_custom_fields();
	} else {
		if(isset($_GET['save_post'])) {
			savePost();
		} elseif(isset($_GET['delete_post']) && $blogUserPermissions['blogdeletepost'] == true) {
			$post_id = urldecode($_GET['delete_post']);
			$delete_post = $Blog->deletePost($post_id);
			if($delete_post == true) {
				echo '<div class="updated">'.i18n_r(BLOGFILE.'/POST_DELETED').'</div>';
			} else {
				echo '<div class="error">'.i18n(BLOGFILE.'/FEED_DELETE_ERROR').'</div>';
			}
		}
    #show_posts_admin
		$all_posts = $Blog->listPosts(true, true); // Get a list of all the posts in the blog
    require_once('html/posts-admin.php'); // Bring in the HTML to show this section
	}
}

/**-------------------------------------------------------------------------------------------------
 * show_settings_admin($slug, $excerpt) 
 * Function used to display and manage the 'Settings' section of the blog admin.
 * 
 * @return:  void (void)
 */ 
function show_settings_admin() {

	# Init function
  global $SITEURL; // Declare required GLOBALS
	$Blog = new Blog; // Bring in the Blog class
  
  # Build the array of setting to save to the blog_settings.xml file
	if(isset($_POST['blog_settings'])) { // The user has submitted an update.
		$blog_settings_array = array( 
      'blogurl' => (!empty($_POST['blog_url'])) ? $_POST['blog_url'] : $Blog->getSettingsData("blogurl"),
      'template' => (!empty($_POST['template'])) ? $_POST['template'] : $Blog->getSettingsData("template"),
      'excerptlength' => (!empty($_POST['excerpt_length'])) ? $_POST['excerpt_length'] : $Blog->getSettingsData("excerptlength"),
      'postformat' => (!empty($_POST['show_excerpt'])) ? $_POST['show_excerpt'] : $Blog->getSettingsData("postformat"),
      'postperpage' => (!empty($_POST['posts_per_page'])) ? $_POST['posts_per_page'] : $Blog->getSettingsData("postperpage"),
      'recentposts' => (!empty($_POST['recent_posts'])) ? $_POST['recent_posts'] : $Blog->getSettingsData("recentposts"),
      'prettyurls' => (!empty($_POST['pretty_urls'])) ? $_POST['pretty_urls'] : $Blog->getSettingsData("prettyurls"),
      'autoimporter' => (!empty($_POST['auto_importer'])) ? $_POST['auto_importer'] : $Blog->getSettingsData("autoimporter"),
      'autoimporterpass' => (!empty($_POST['auto_importer_pass'])) ? $_POST['auto_importer_pass'] : $Blog->getSettingsData("autoimporterpass"),
      'rsstitle' => (!empty($_POST['rss_title'])) ? $_POST['rss_title'] : $Blog->getSettingsData("rsstitle"),
      'rssinclude' => (!empty($_POST['rss_include'])) ? $_POST['rss_include'] : $Blog->getSettingsData("rssinclude"),
      'rssdescription' => (!empty($_POST['rss_description'])) ? $_POST['rss_description'] : $Blog->getSettingsData("rssdescription"),
      'rssfeedposts' => (!empty($_POST['rss_feed_num_posts'])) ? $_POST['rss_feed_num_posts'] : $Blog->getSettingsData("rssfeedposts"),
      'archivepostcount' => (!empty($_POST['display_archives_post_count'])) ? $_POST['display_archives_post_count'] : $Blog->getSettingsData("archivespostcount"),
    );
    
    # Attempt to save the settings array to file.
		if($Blog->saveSettings($blog_settings_array)) { // Success: Notify the user.
      echo '<script>clearNotify();notifyOk(\''.i18n_r(BLOGFILE.'/SETTINGS_SAVE_OK').'\').popit().removeit();</script>';
    } else { // Failed: Notify the user.
      echo '<script>clearNotify();notifyError(\''.i18n_r(BLOGFILE.'/SETTINGS_SAVE_ERROR').'\').popit().removeit();</script>';
    }
	}
  
  # Include the HTML for the settings page we are trying to display.
  if ($_GET['settings'] == 'rss') { // RSS Auto-Importer settings
    require_once('html/settings-rss.php');
  } else { // The default main settings page
    require_once('html/settings-main.php');
  }
}

/**-------------------------------------------------------------------------------------------------
 * editPost($post_id)
 * Edit/Create post screen
 * 
 * @param $post_id (string) The id of the post to edit. Null if creating new post
 * @return void (void)
 */  
function editPost($post_id=null) {

	GLOBAL $SITEURL;
	$Blog = new Blog;
  
	if($post_id != null) {
		$blog_data = getXML(BLOGPOSTSFOLDER.$post_id.'.xml');
	} else {
		$blog_data = $Blog->getXMLnodes();
	}
	require_once('html/post-editor.php');
	include(BLOGPLUGINFOLDER.'ckeditor.php');
}

/**-------------------------------------------------------------------------------------------------
 * category_dropdown($current_category)
 * Echos all categories to place into a select menu
 * 
 * @param  $current_category (string) Name of the current category. Marks it as selected.
 * @return void              (void)
 */  
function category_dropdown($current_category=null) {

	$category_file = getXML(BLOGCATEGORYFILE);	
  
	foreach($category_file->category as $category_item)	{		
		$category_item = (string) $category_item;
		if($category_item == $current_category) {
			echo '<option value="'.$current_category.'" selected>'.$current_category.'</option>';	
		} else {
			echo '<option value="'.$category_item.'">'.$category_item.'</option>';	
		}	
	}	
  
	if($current_category == null) {
		echo '<option value="" selected></option>';	
	} else {
		echo '<option value=""></option>';	
	}
}

/**-------------------------------------------------------------------------------------------------
 * savePost() 
 * Saves A Post
 * 
 * @return void (void) success or error message
 */  
function savePost() {

	$Blog = new Blog;
	$xmlNodes = $Blog->getXMLnodes(true);
  
	if(isset($_POST['post-title'])) {
		foreach($xmlNodes as $key => $value) {
			if(!isset($_POST["post-".$key])) {$post_value = '';}
      else {$post_value = $_POST["post-".$key];}
			$post_data[$key] = $post_value;
		}
		$savePost = $Blog->savePost($post_data);
		$generateRSS = $Blog->generateRSSFeed();
		if($savePost != false) {
			echo '<div class="updated">'.i18n_r(BLOGFILE.'/POST_ADDED').'</div>';
		} else {
			echo '<div class="error">'.i18n_r(BLOGFILE.'/POST_ERROR').'</div>';
		}
	} else {
		echo '<div class="error">'.i18n_r(BLOGFILE.'/BLOG_CREATE_EDIT_NO_TITLE');
		echo ' <a href="javascript:history.go(-1)">'.i18n_r(BLOGFILE.'/BLOG_RETURN_TO_PREVIOUS_PAGE').'</a>';
		echo '</div>';
	}
}

/**-------------------------------------------------------------------------------------------------
 * blog_theme_layouts()
 * Returns a list of layout templates for the blog
 * 
 * @return $list (array) List of available templates
 */
function blog_theme_layouts() {

  $files = array_filter(glob(BLOGPLUGINFOLDER.'templates/*.php'), 'is_file'); // Get the list of template files
  $list = array(); // Prepare the $list array
  
  foreach ($files as $file) { // For each file in the list
    array_push($list, pathinfo($file, PATHINFO_FILENAME)); // Get its name without path or extention
  }
  
  return $list; // Return the list
}
