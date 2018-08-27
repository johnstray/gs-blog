<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * @file: adminFunctions.php
 * @package: GetSimple Blog [plugin]
 * @action: Administration
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
	GLOBAL $SITEURL, $GSASMIN, $SESSIONHASH;

	if(isset($_GET['edit_post'])) {
		editPost($_GET['edit_post']);
	} elseif(isset($_GET['create_post'])) {
		editPost();
	} elseif(isset($_GET['categories'])) {
		if(isset($_GET['add_category'])) {
			$add_category = $Blog->saveCategory($_POST['new_category']);
			if($add_category == true) {
				display_message(i18n_r(BLOGFILE.'/CATEGORY_ADDED'), 'ok', true);
			} else {
				display_message(i18n_r(BLOGFILE.'/CATEGORY_ERROR'), 'error');
			}
		}
		if(isset($_GET['delete_category'])) {
			$delete_category = $Blog->deleteCategory($_GET['delete_category']);
            if($delete_category == true) {
                display_message(i18n_r(BLOGFILE.'/CATEGORY_DELETED'), 'ok', true);
            } else {
                display_message(i18n_r(BLOGFILE.'/CATEGORY_DELETE_ERROR'), 'error');
            }
		}
        if ( isset( $_GET['edit_category'] ) ) {
            $edit_category = $Blog->updateCategory( $_POST['previousName'], $_POST['newName'] );
            if ( $edit_category == true ) {
                display_message( i18n_r( BLOGFILE . '/CATEGORY_UPDATED' ) );
            } else {
                display_message( i18n_r( BLOGFILE . '/CATEGORY_UPDATE_ERROR' ) );
            }
        }
    #edit_categories
		$category_file = getXML(BLOGCATEGORYFILE);
    require_once('html/category-management.php');
	} elseif(isset($_GET['auto_importer'])) {
		if(isset($_POST['post-rss'])) {
			$post_data = array();
			$post_data['name'] = $_POST['post-rss'];
			$post_data['category'] = $_POST['post-category'];
			$add_feed = $Blog->saveRSS($post_data);
			if($add_feed == true) {
				display_message(i18n_r(BLOGFILE.'/FEED_ADDED'), 'ok', true);
			} else {
				display_message(i18n_r(BLOGFILE.'/FEED_ERROR'), 'error');
			}
		} elseif(isset($_GET['delete_rss'])) {
			$delete_feed = $Blog->deleteRSS($_GET['delete_rss']);
			if($delete_feed == true) {
				display_message(i18n_r(BLOGFILE.'/FEED_DELETED'), 'ok', true);
			} else {
				display_message(i18n_r(BLOGFILE.'/FEED_DELETE_ERROR'), 'error');
			}
		}
        #edit_rss
		$rss_file = getXML(BLOGRSSFILE);
    require_once('html/feed-management.php');
	} elseif(isset($_GET['settings'])) {
		show_settings_admin();
	} elseif(isset($_GET['update'])) {
		show_update_admin();
	} elseif(isset($_GET['help'])) {
		require_once('html/help-admin.php');
	} elseif(isset($_GET['custom_fields'])) {
		$CustomFields = new customFields;
		if(isset($_POST['save_custom_fields'])) {
			$saveCustomFields = $CustomFields->saveCustomFields();
			if($saveCustomFields) {
				display_message(i18n_r(BLOGFILE.'/EDIT_OK'), 'ok', true);
			}
		}
		show_custom_fields();
    } elseif( isset( $_GET['search'] ) && isset( $_GET['filter'] ) ) { # Search and Filter Function
  
        ( $filtered_posts = $Blog->searchPosts( $_GET['search'], $_GET['filter'] ) ) || ( $filtered_posts = array() );
        $all_posts = $filtered_posts;
        require_once( 'html/posts-admin.php' );
    
    } else {
        if(isset($_GET['save_post'])) {
            savePost();
        } elseif(isset($_GET['delete_post'])) {
            $post_id = urldecode($_GET['delete_post']);
            $delete_post = $Blog->deletePost($post_id);
            if($delete_post == true) {
                display_message(i18n_r(BLOGFILE.'/POST_DELETED'), 'ok', true);
            } else {
               display_message(i18n(BLOGFILE.'/POST_DELETE_ERROR'), 'error');
            }
        }
        #show_posts_admin
        $all_posts = $Blog->listPosts(true, true); // Get a list of all the posts in the blog
        require_once('html/posts-admin.php'); // Bring in the HTML to show this section
    }
    
    $year = date('Y');
    echo "</div><div class=\"copyright-text\">GetSimple Blog Plugin &copy; 2014 - {$year} John Stray - Licenced under <a href=\"https://www.gnu.org/licenses/gpl-3.0.en.html\">GNU GPLv3</a>";
    echo "<div>If you like this plugin or have found it useful, please consider a <a href=\"https://paypal.me/JohnStray\">donation</a></div>";
    
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
		$existing_settings = $Blog->getSettingsData();
    $updated_settings = array();
    foreach ($existing_settings as $key => $value) {
      $updated_settings[$key] = (!empty($_POST[$key])) ? $_POST[$key] : $value;
    }
    
    # Attempt to save the settings array to file.
    if($Blog->saveSettings($updated_settings)) { // Success: Notify the user.
      display_message(i18n_r(BLOGFILE.'/SETTINGS_SAVE_OK'), 'ok', true);
    } else { // Failed: Notify the user.
      display_message(i18n_r(BLOGFILE.'/SETTINGS_SAVE_ERROR'), 'error');
    }
	}
  
  # Include the HTML for the settings page we are trying to display.
  if ($_GET['settings'] == 'rss') { // RSS Auto-Importer settings
    require_once('html/settings-rss.php');
  } elseif ($_GET['settings'] == 'seo') {
    require_once('html/settings-seo.php');
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
	
	# Removing this include as it was causeing CKEditor to load twice against 'post-content' textbox.
	# Not sure why this was here to begin with as Custom Fields manages the setup of CKEditor.
	# include(BLOGPLUGINFOLDER.'ckeditor.php');
}

/**
 * Generates a list of templates that can be applied to a blog post
 *
 * @param $current (string) - Name of the current template. Marks it as selected
 * @return (array) - A list of template files available
 */
function generateTemplateList( $current = null, $echo = true ) {
    GLOBAL $TEMPLATE;
    $templates = array();
    $templatePath = GSTHEMESPATH . $TEMPLATE;
    $templateFiles = @scandir($templatePath);
    if ( $templateFiles !== false ) {
        foreach ( $templateFiles as $templateFile ) {
            if ( isFile($templateFile, $templatePath, 'php') && stripos($templateFile, 'blog.') === 0 ) {
                $templates[] = $templateFile;
            }
        }
        
        if ( $echo && !empty($templates) ) {
            echo '<option value="default"';
            if ( $current == null || $current == 'default') { echo ' selected="selected"'; }
            echo '>Default Template</option>';
            foreach ( $templates as $template ) {
                echo '<option value="' . $template .'"';
                if ( $current !== null && $template == $current) { echo ' selected="selected"'; }
                echo '>' . $template . '</option>';
            }
        }
        
    } else {
        debugLog("Unable to generate a list of available templates. Theme directory no accessible");
        return false;
    }
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
    exec_action('chagedata-save'); // Added to allow for compatibility with other plugins
		if($savePost != false) {
			display_message(i18n_r(BLOGFILE.'/POST_ADDED'), 'ok', true);
		} else {
			display_message(i18n_r(BLOGFILE.'/POST_ERROR'), 'error');
		}
	} else {
		display_message(i18n_r(BLOGFILE.'/BLOG_CREATE_EDIT_NO_TITLE').' <a href="javascript:history.go(-1)">'.i18n_r(BLOGFILE.'/BLOG_RETURN_TO_PREV_PAGE').'</a>', 'error');
	}
}
