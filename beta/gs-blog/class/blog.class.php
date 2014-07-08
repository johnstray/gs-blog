<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple Blog
 * Description: Manages a blog for you website
 * 
 * File: blog.class.php
 * This is where all the magic happens
 */

class gsBlog
{
 /**
  * Construct
  * Manages initial setup, creating files as required.
  * 
  * @return void
  */
  public function __construct()
  {
    # Posts posts directory
    if(!file_exists(GSBLOGPOSTSFOLDER)) { // Create posts directory if not exists
      if(mkdir(GSBLOGPOSTSFOLDER)) { // Was directory creation successful?
        gsBlog_umsg(i18n_r(GSBLOG.'/POSTSFOLDER_SUCCESS','OK')); // Yes!
      } else {
        gsBlog_umsg(i18n_r(GSBLOG.'/POSTSFOLDER_FAILED','ERROR')); // Nope!
      }
    }
    
    # Settings File
    if(!file_exists(GSBLOGSETTINGSFILE)) { // Check for existing settings file.
      $settings_array = array( // Create the default settings
        'blogurl' => 'index' // Slug for page to display blog
      );
      if(file_exists(GSDATAOTHERPATH.'blog_settings.xml') { // Are we upgrading from a previous version?
        if($this->upgrade('settings')) { // Do upgrade and get status
          gsBlog_umsg(i18n_r(GSBLOG.'/SETTINGSFILE_UPGRADED','OK')); // Upgraded successfully
        } else {
          gsBlog_umsg(i18n_r(GSBLOG.'/SETTINGSFILE_UPGFAIL','ERROR')); // Upgrade failed
        }
      } else { // It's a fresh install, create new settings file
        if($this->saveSettings($settings_array)) { // Was the save successful?
          gsBlog_umsg(i18n_r(GSBLOG.'/SETTINGSFILE_SUCCESS','OK')); // Yes!
        } else {
          gsBlog_umsg(i18n_r(GSBLOG.'/SETTINGSFILE_FAILED','ERROR')); // Nope!
        }
      }
    }
    
    # Categories Directory
    if(!file_exists(GSBLOGCATEGORIESFOLDER)) { // Check for existing Categories directory
      if(file_exists(GSDATAOTHERPATH.'blog_categories.xml') { // Are we upgrading from a previous version?
        if($this->upgrade('categories')) { // Do upgrade and get status
          gsBlog_umsg(i18n_r(GSBLOG.'/CATEGORYFOLDER_UPGRADED')); // Upgraded successfully
        } else {
          gsBlog_umsg(i18n_r(GSBLOG.'/CATEGORYFOLDER_UPGFAIL')); // Upgrade failed
        }
      } else { // It's a fresh install, create new Categories directory
        if(mkdir(GSBLOGCATEGORIESFOLDER)) { // Was directory creation successful?
          gsBlog_umsg(i18n_r(GSBLOG.'/CATEGORYFOLDER_SUCCESS')); // Yes!
        } else {
          gsBlog_umsg(i18n_r(GSBLOG.'/CATEGORYFOLDER_FAILED')); // Nope!
        }
      }
    }
  }
  
 /**
  * savePost($post_data)
  * Saves a post as an XML file in the posts diretory.
  * 
  * @param  (array)$post_data : The post data (array / SimpleXML Object)
  * @return (boolean)$success : True if save was successful, false otherwise
  */
  public function savePost($post_data)
  {
    # Create a slug for the post.
    if($post_data['slug'] != '') {
      $slug = $this->createSlug($post_data['slug']); // Slug provided
    } else {
      $slug = $this->createSlug($post_data['title']); // Slug created from title
    }
    
    $postFile = GSBLOGPOSTSFOLDER.$slug.'.xml';
    
    # @TODO: This section needs figuring out and commenting...
    if($post_data['current_slug'] == '' || $post_data['current_slug'] != $post_data['slug']) {
			if ($post_data['current_slug'] != '') {
				unlink(GSBLOGPOSTSFOLDER . $post_data['current_slug'] . '.xml');
			}
			if (file_exists($file) && $auto_import == false) {
				$count = 0;
				while(file_exists($file)) {
					$file = GSBLOGPOSTSFOLDER . "$slug-" . ++$count . '.xml';
					$slug .= "-$count";
				}
			}
		} else {
			unlink(GSBLOGPOSTSFOLDER . $post_data['current_slug'] . '.xml');
		}
    
    # Prepare the data so it's safe for XML
    if($post_data['date'] == '') { // Date: Set if not given
      $post_data['date'] = date('m/d/Y h:i:s a', time());
    }
    if($post_data['tags'] != '') { // Tags: Format if given
      $post_data['tags'] = str_replace(array(' ', ',,'), array('', ','),$post_data['tags']);
    }
    if(is_array($post_data['category']) { // Categories: Implode to string if more than one given
      $post_data['category'] = implode('|',$post_data['category']);
    }
    $post_data['title'] = safe_slash_html($post_data['title']); // Title: Make it safe
    $post_data['content'] = safe_slash_html($post_data['content']) // Content: Make it safe
    
    # Create the XML object
    $xml = new SimpleXMLExtended('<?xml version="1.0"?><item></item>'); // Create an XML object
    $skip = array('current_slug'); // Post data $keys to skip adding to XML file.
    foreach($post_data as $key => $value) {
      if(!in_array($key,$skip)) { // Are we skipping this $key?
        $node = $xml->addChild($key); // Create XML child
        $node->addCData($value); // Add XML child's data
      }
    }
    
    # Save the XML file
    if(XMLsave($xml,$file)) {
      // Save successful: Update cache and search index
      $this->createPostsCache(); // Update Cache
      $this->searchIndexer($post_data); // Pass data for search indexing
      gsBlog_debug('XMLsave: OK - '.$post_data['slug']); // Notify the debug
      return true;
    } else {
      // Save failed: Notify the debug
      gsBlog_debug('XMLsave: FAIL - '.$post_data['slug']);
      return false;
    }
  }
  
 /**
  * getPost($post_id)
  * Reads the XML file for the specified post
  * 
  * @param  (string)$post_id  : The slug of the post to read
  * @return (array)$post_data : Data for the requested post
  * @return (boolean)false    : False if post doesn't exist
  */
  public function getPost($post_id)
  {
    if(file_exists(GSBLOGPOSTSFOLDER.$post_id.'.xml')) { // Does the post exists?
      $xml = getXML($post_id); // Get the XML data from $post_id.xml
      $array = simpleXMLToArray($xml); // Convert the XML to and array
      return $array; // Return the array of post data.
    } else { // The post doesn't exist
      gsBlog_debug('getPost: FAIL - '.$post_id.'.xml ',i18n_r(GSBLOG.'/DOESNT_EXIST')); // Notify the debug
      return false;
    }
  }
  
 /**
  * listPosts()
  * Returns a multi-dimensional array of posts with their data.
  * 
  * @return (multi-array)$posts : Array of posts with data
  * @return (boolean)false      : False if no posts found
  */
  public function listPosts()
  {
    # Get post files
    $files = glob(GSBLOGPOSTSFOLDER.'*.xml';
    
    if(is_array($files)) { // Is $files an array?
      # Convert files to slugs
      foreach($files as $file) {
        $slugs[] = basename($file, '.xml');
      }
      
      # Create posts array from slugs
      foreach ($slugs as $slug) {
        $posts[$slug] = $this->getPost($slug);
      }
      
      # Sort the posts array by date
      $sort = array();
      foreach($posts as $key = $row) {
        $sort[$key] = $row['date'];
      }
      array_multisort($sort, SORT_DESC, $posts);
      
      # Return the array
      return $posts;
    } else { // $files is false/empty (returned by glob) so there are no posts.
      gsBlog_debug('listPosts: WARN - '.i18n_r(GSBLOG.'/NO_FILES_FOUND')); // Notify the debug
      return false; // Return false
    }
  }
  
 /**
  * deletePost($post_id)
  * Deletes a blog post
  * 
  * @param (string)$post_id   : Slug of the post to delete
  * @return (boolean)$success : True if successful, false otherwise
  */
  public function deletePost($post_id)
  {
    if(file_exists(GSBLOGPOSTSFOLDER.$post_id.'.xml')) { // Does the file exist?
      if(unlink(GSBLOGPOSTSFOLDER.$post_id.'.xml')) { // Attempt to delete...
        // Success!
        gsBlog_debug('Unlink: OK - '.$post_id.'.xml'); // Notify the debug
        return true;
      } else { // Failed to delete file
        gsBlog_debug('Unlink: FAIL - '.$post_id.'.xml'); // Notify the debug
        return false;
      }
    } else { // File doesn't exists
      gsBlog_debug('deletePost: FAIL - '.$post_id.'.xml '.i18n_r(GSBLOG.'/DOESNT_EXIST')); // Notify the debug
      return false;
    }
  }
  
 /**
  * simpleXMLToArray()
  * Converts a simpleXML element into an array.
  * 
  * Thanks to: <xananax@yelostudio.com> at php.net
  */
  private function simpleXMLToArray(SimpleXMLElement $xml,$attributesKey=null,$childrenKey=null,$valueKey=null){ 

    if($childrenKey && !is_string($childrenKey)){$childrenKey = '@children';} 
    if($attributesKey && !is_string($attributesKey)){$attributesKey = '@attributes';} 
    if($valueKey && !is_string($valueKey)){$valueKey = '@values';} 

    $return = array(); 
    $name = $xml->getName(); 
    $_value = trim((string)$xml); 
    if(!strlen($_value)){$_value = null;}; 

    if($_value!==null){ 
      if($valueKey){$return[$valueKey] = $_value;} 
      else{$return = $_value;} 
    } 

    $children = array(); 
    $first = true; 
    foreach($xml->children() as $elementName => $child){ 
      $value = simpleXMLToArray($child,$attributesKey, $childrenKey,$valueKey); 
      if(isset($children[$elementName])){ 
        if(is_array($children[$elementName])){ 
          if($first){ 
            $temp = $children[$elementName]; 
            unset($children[$elementName]); 
            $children[$elementName][] = $temp; 
            $first=false; 
          } 
          $children[$elementName][] = $value; 
        }else{ 
          $children[$elementName] = array($children[$elementName],$value); 
        } 
      } 
      else{ 
        $children[$elementName] = $value; 
      } 
    } 
    if($children){ 
      if($childrenKey){$return[$childrenKey] = $children;} 
      else{$return = array_merge($return,$children);} 
    } 

    $attributes = array(); 
    foreach($xml->attributes() as $name=>$value){ 
      $attributes[$name] = trim($value); 
    } 
    if($attributes){ 
      if($attributesKey){$return[$attributesKey] = $attributes;} 
      else{$return = array_merge($return, $attributes);} 
    } 

    return $return; 
  }
  
}