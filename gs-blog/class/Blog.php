<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/** 
* The Blog Cass
* Handles all major methods
* 
* @return void 
*/  
class Blog 
{
	/** 
	* Construct
	* Creates data/blog_posts directory if it is not already created.
	* Creates blog category file if it is not yet created
	* Creates blog settings file if it is not yet created
	* Crates blog rss feed auto importer file if it is not yet created
	* 
	* @return void
	*/  
	public function __construct() {
    
    # Blog posts folder
    if(!file_exists(BLOGPOSTSFOLDER)) {
      if(mkdir(BLOGPOSTSFOLDER)) {
        display_message(i18n_r(BLOGFILE.'/DATA_BLOG_DIR'), 'ok', true);
      } else {
        display_message('<strong>'.i18n_r(BLOGFILE.'/DATA_BLOG_DIR_ERR').'</strong><br/>'.i18n_r(BLOGFILE.'/DATA_BLOG_DIR_ERR_HINT'), 'error');
      }
    }
    
    # Categories File
    if(!file_exists(BLOGCATEGORYFILE)) {
      $xml = new SimpleXMLExtended('<?xml version="1.0"?><item></item>');
      if(XMLsave($xml, BLOGCATEGORYFILE)) {
        display_message(i18n_r(BLOGFILE.'/DATA_BLOG_CATEGORIES'), 'ok', true);
      } else {
        display_message(i18n_r(BLOGFILE.'/DATA_BLOG_CATEGORIES_ERR'), 'error');
      }
    }
    
    # RSS Feed File
    if(!file_exists(BLOGRSSFILE)) {
      $xml = new SimpleXMLExtended('<?xml version="1.0"?><item></item>');
      if(XMLsave($xml, BLOGRSSFILE)) {
        display_message(i18n_r(BLOGFILE.'/DATA_BLOG_RSS'), 'ok', true);
      } else {
        display_message(i18n_r(BLOGFILE.'/DATA_BLOG_RSS_ERR'), 'error');
      }
    }
    
    # The Settings File
    $default_settings = array(
      'blogurl'           => "index",
      'prettyurls'        => "N",
      'lang'              => "en_US",
      'rsstitle'          => "My RSS Feed",
      'rssdescription'    => "Welcome to my blog!!",
      'rssfeedposts'      => "10",
      'rssinclude'        => "N",
      'excerptlength'     => "350",
      'postformat'        => "N",
      'postperpage'       => "10",
      'recentposts'       => "4",
      'achivespostcount'  => "Y",
      'autoimporter'      => "N",
      'autoimporterpass'  => "passphrase",
      'uploaderpath'      => "",
    );
    if(!file_exists(BLOGSETTINGS)) {
      if($this->saveSettings($default_settings)) {
        display_message(i18n_r(BLOGFILE.'/BLOG_SETTINGS').' '.i18n_r(BLOGFILE.'/WRITE_OK'), 'ok', true);
      } else {
        display_message(i18n_r(BLOGFILE.'/BLOG_SETTINGS').' '. i18n_r(BLOGFILE.'/DATA_FILE_ERROR'), 'error');
      }
    } else {
      $saved_settings = $this->getSettingsData();
      $update_settings = false;
      
      # Check for missing settings
      $missing_settings = array_diff_key($default_settings, $saved_settings);
      if(count($missing_settings) > 0) {
        foreach ($missing_settings as $key => $value) {
          $saved_settings[$key] = $value;
        } $update_settings = true;
      }
      
      # Check for redundant settings
      foreach($saved_settings as $key => $value) {
        if(!array_key_exists($key, $default_settings)) {
          unset($saved_settings[$key]);
          $update_settings = true;
        }
      }
      
      # Write the settings to file after update
      if($update_settings === true) {
        if($this->saveSettings($default_settings)) {
          display_message(i18n_r(BLOGFILE.'/BLOG_SETTINGS').' '.i18n_r(BLOGFILE.'/WRITE_OK'), 'ok', true);
        } else {
          display_message(i18n_r(BLOGFILE.'/BLOG_SETTINGS').' '. i18n_r(BLOGFILE.'/DATA_FILE_ERROR'), 'error');
        }
      }
    }
    
    # Reserved Custom Fields
    if(!file_exists(BLOGCUSTOMFIELDS)) {
      $custom_fields_file = BLOGPLUGINFOLDER.'inc/reserved_blog_custom_fields.xml';
      if(!copy($custom_fields_file, BLOGCUSTOMFIELDS)) {
        display_message('<strong>Catastrophic ERROR!!!</strong> - You are going to need to copy the contents of the below file, save it as a new document named "blog_custom_fields.xml" and then move it to the "'.GSDATAOTHERPATH.'" folder!<br/><strong>XML File To Copy:</strong> '.BLOGCUSTOMFIELDS, 'error');
      }
    }
    
  }

	/** 
	* Lists All Blog Posts
	* 
	* @param $array bool if true an array containing each posts filename and publish date will be returned instead of only the filename
	* @param $sort_dates bool if true the posts array will be sorted by post date -- THIS REQUIRES $array param TO BE TRUE
	* @return array the filenames & paths of all posts
	*/  
	public function listPosts($array=false, $sort_dates=false)
	{
		$all_posts = glob(BLOGPOSTSFOLDER . "*.xml");
		if(($all_posts===false) | (count($all_posts) < 1))
		{
			return false;
		}
		else
		{
			$count = 0;			
			if($array==false)
			{
				return $all_posts;
			}
			else
			{
				foreach($all_posts as $post)
				{
					$data = getXML($post);
                    $posts[$count]['filename'] = $post;
                    $posts[$count]['date'] = (string) $data->date;
                    $posts[$count]['category'] = (string) $data->category;
                    $posts[$count]['tags'] = (string) $data->tags;
                    if(isset($data->author)) { $posts[$count]['author'] = (string) $data->author; }
                    $count++;
				}
				if($sort_dates != false && $array != false)
				{
					usort($posts, array($this, 'sortDates'));  
				}
				return $posts;
			}
		}
	}



	/** 
	* Get Data From Settings File
	* 
	* @param $field the node of the setting to retrieve
	* @return string requested blog settings data
	*/  
	public function getSettingsData($field=null)
	{
		$settingsData = getXML(BLOGSETTINGS);
		if(!is_null($field))
		{
			if(is_object($settingsData->$field))
			{
				return $settingsData->$field;	
			}
			else
			{
				return false;
			}
		}
		else
		{
			$settingsArray = array();
			foreach($settingsData as $settingsNode => $settingsNodeValue)
			{
				$settingsArray[$settingsNode] = (string) $settingsNodeValue;
        // Get the Language Code from the loaded language file instead of using stored settings.
        // This allows for the language setting to be constantly up to date without the need to save settings to update.
        $languageValues = i18n_r(BLOGFILE.'/LANGUAGE_CODE');
        $settingsArray['lang'] = $languageValues[0];
			}
			return $settingsArray;
		}
	}

	/** 
	* Get A Blog Post
	* 
	* @param $post_id the filename of the blog post to retrieve
	* @return array blog xml data
	*/  
	public function getPostData($post_id)
	{
		$post = getXML($post_id);
		return $post;
	}

	/** 
	* Saves a post submitted from the admin panel
	* 
	* @param $post_data the post data (eg: 'XML_FIELD_NAME => $POSTDATA')
	* @todo clean up this method... Not happy about it's messiness!
	* @return bool
	*/  
	public function savePost($post_data, $auto_import=false)
	{
		if ($post_data['slug'] != '')
		{
			$slug = $this->blog_create_slug($post_data['slug']);
		}
		else
		{
			$slug = $this->blog_create_slug($post_data['title']);
		}
		$file = BLOGPOSTSFOLDER . "$slug.xml";
		if($post_data['current_slug'] == '' || $post_data['current_slug'] != $post_data['slug'])
		{
			# delete old post file
			if ($post_data['current_slug'] != '')
			{
				unlink(BLOGPOSTSFOLDER . $post_data['current_slug'] . '.xml');
			}
			# do not overwrite existing files
			if (file_exists($file) && $auto_import == false) 
			{
				$count = 0;
				while(file_exists($file))
				{
					$file = BLOGPOSTSFOLDER . "$slug-" . ++$count . '.xml';
					$slug .= "-$count";
				}
			}
		}
		else
		{
			unlink(BLOGPOSTSFOLDER . $post_data['current_slug'] . '.xml');
		}


		if($post_data['date'] != '')
		{
			$date = date('Y-m-d H:i:s',strtotime($post_data['date']));
		} 
		else
		{
			$date = date('Y-m-d H:i:s', time());
		}
		if($post_data['tags'] != '')
		{
			$tags = str_replace(array(' ', ',,'), array('', ','),$post_data['tags']);
		}
		else
		{
			$tags = '';
		}

		$xml = new SimpleXMLExtended('<?xml version="1.0"?><item></item>');
		foreach($post_data as $key => $value)
		{
			if($key == 'current_slug' || $key == 'time')
			{

			}
			elseif($key == 'slug')
			{
				$node = $xml->addChild($key);
				$node->addCData($slug);
			}
			elseif($key == 'title')
			{
				$title = safe_slash_html($value);
				$node = $xml->addChild($key);
				$node->addCData($title);
			}
			elseif($key == 'date')
			{
				$node = $xml->addChild($key);
				$node->addCData($date);
			}
			elseif($key == 'content')
			{
  			  $content = safe_slash_html($value);
				$node = $xml->addChild($key);
				$node->addCData($content);
			}
			elseif($key == 'tags')
			{
				$node = $xml->addChild($key);
				$node->addCData($tags);
			}
			else
			{
				$node = $xml->addChild($key);
				$node->addCData($value);
			}
		}
		    $tags = str_replace(array(' ', ',,'), array('', ','), safe_slash_html($post_data['tags']));
		if (! XMLsave($xml, $file))
		{
			return false;
		}
		else
		{
			$this->createPostsCache();
			if (function_exists('i18n_search_index_item')) {
				i18n_search_index_item($slug, 'en', $post_data['date'], $post_data['date'], $post_data['tags'], $post_data['title'], $post_data['content']);
			}
			return true;
		}

	}

	/** 
	* Deletes a blog post
	* 
	* @param $post_id id of the blog post to delete
	* @return bool
	*/  
	public function deletePost($post_id)
	{
		if(file_exists(BLOGPOSTSFOLDER.$post_id.'.xml'))
		{
			$delete_post = unlink(BLOGPOSTSFOLDER.$post_id.'.xml');
			if($delete_post)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/** 
	* Saves category added or edited
	* 
	* @param $category the category name
	* @param $existing whether the category exists already
	* @todo  use $existing param to edit a category instead of deleting it. This would also need to go through and change the category for any posts using the edited category
	* @return bool
	*/  
	public function saveCategory($category, $existing=false)
	{
		$category_file = getXML(BLOGCATEGORYFILE);
		$xml = new SimpleXMLExtended('<?xml version="1.0"?><item></item>');
		foreach($category_file->category as $ind_category)
		{
			$parent_nodes_node = $xml->addChild('category');
				$parent_nodes_node->addCData($ind_category);
		}
		$parent_nodes_node = $xml->addChild('category');
			$parent_nodes_node->addCData($category);
		$add_category = XMLsave($xml, BLOGCATEGORYFILE);
		if($add_category)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/** 
	* Deletes a category
	* 
	* @param $catgory Category to delete
	* @return bool
	*/  
	public function deleteCategory($category)
	{
		$category_file = getXML(BLOGCATEGORYFILE);
		$xml = new SimpleXMLExtended('<?xml version="1.0"?><item></item>');
		foreach($category_file->category as $ind_category)
		{
			if($ind_category == $category)
			{
				//Do Nothing (Deletes Category)
			}
			else
			{
				$xml->addChild('category', $ind_category);
			}
		}
		$delete_category = XMLsave($xml, BLOGCATEGORYFILE);
		if($delete_category)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

    /**
    * Update category
    *
    * @param $before Category's current name
    * @param $after Category's new name
    * @return bool
    */
    public function updateCategory( $before, $after )
    {
        if ( this->deleteCategory( $before ) ) {
            if ( this->saveCategory( $after ) ) {
                
                $all_posts = $this->listPosts();
                $error = false;
                
                foreach ( $all_posts as $post )
                {
                    $postdata = $this->getPostData( $post );
                    $postdata['category'] = $after;
                    if ( !$this->savePost( $postdata ) ) { $error = true; break; }
                }
                
                if ( $error ) { return false; } else { return true; }
                
            } else { return false; }
        } else { return false; }
    }

	/** 
	* Saves RSS feed added or edited
	* 
	* @param $new_rss array all of the posts data
	* @param $existing whether the rss is new
	* @todo  posssible add functionality of editing a feed using the $existing param. Not sure if this is even needed
	* @return bool
	*/  
	public function saveRSS($new_rss, $existing=false)
	{
		$rss_file = getXML(BLOGRSSFILE);
		$xml = new SimpleXMLExtended('<?xml version="1.0"?><item></item>');
		$count = 0;
		foreach($rss_file->rssfeed as $rss_feed)
		{
			$rss_atts = $rss_feed->attributes();
			$rss = $xml->addChild('rssfeed');

			$rss->addAttribute('id', $count);

			$rss_name = $rss->addChild('feed');				
			$rss_name->addCData($rss_feed->feed);
			
			$rss_category = $rss->addChild('category');	
			$rss_category->addCData($rss_feed->category);
			$count++;
		}
		$newfeed = $xml->addChild('rssfeed');
		$newfeed->addAttribute('id', $count);
		$newfeed_name = $newfeed->addChild('feed');
		$newfeed_name->addCData($new_rss['name']);
		$newfeed_category = $newfeed->addChild('category');
		$newfeed_category->addCData($new_rss['category']);

		$add_rss = XMLsave($xml, BLOGRSSFILE);
		if($add_rss)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/** 
	* Delete RSS Feed
	* 
	* @param $feed_id RSS feed to delete
	* @return bool
	*/  
	public function deleteRSS($feed_id)
	{
		$rss_file = getXML(BLOGRSSFILE);
		$xml = new SimpleXMLExtended('<?xml version="1.0"?><item></item>');
		$count = 0;
		foreach($rss_file->rssfeed as $rss_feed)
		{
			$rss_atts = $rss_feed->attributes();
			if($feed_id == $rss_atts['id'])
			{

			}
			else
			{
				$rss = $xml->addChild('rssfeed');

				$rss->addAttribute('id', $count);

				$rss_name = $rss->addChild('feed');				
				$rss_name->addCData($rss_feed->feed);

				$rss_category = $rss->addChild('category');	
				$rss_category->addCData($rss_feed->category);
			}
			$count++;
		}
		$delete_rss = XMLsave($xml, BLOGRSSFILE);
		if($delete_rss)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/** 
	* Save Blog Plugin Settings
	* 
	* @param array $post_data The array of each xml node to be added. The key for each array item will be the node and the value will be the nodes contents
	* @return bool
	*/  
	public function saveSettings($post_data)
	{

		$xml = new SimpleXMLExtended('<?xml version="1.0"?><item></item>');
		foreach($post_data as $key => $value)
		{
			$parent_nodes_node = $xml->addChild($key);
				$parent_nodes_node->addCData(is_array($value)?strval($value[0]):strval($value));
		}
		$blog_settings = XMLsave($xml, BLOGSETTINGS);
		if($blog_settings)
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	/** 
	* Gets fields for blog post xml files
	* 
	* @param $array bool if the xml nodes should be returned as an array (true) or a object (null or false)
	* @todo this function will be very usefull once custom fields are implemented. For now it is here for preparation for the inevitable!
	* @return array xml nodes if $array param is true
	* @return object xml nodes if $array param is false
	*/  
	public function getXMLnodes($array=false)
	{
		$cfData = getXML(BLOGCUSTOMFIELDS);
		$blog_data = array('current_slug' => '', 'thumbnail' => '', 'thumbalt' => '');
		foreach($cfData->item as $custom_field)
		{
			$value = (string) $custom_field->desc;
			$blog_data[$value] = '';
		}

		if($array == false)
		{
			return $blog_data = (object) $blog_data;
		}
		else
		{
			return $blog_data;
		}
  	}

	/** 
	* Generates link to blog or blog area
	* 
	* @param $query string Optionally you can provide the type of blog url you are looking for (eg: 'post', 'category', 'archive', etc..)
	* @return url to requested blog area
	*/  
	public function get_blog_url($query=FALSE) 
	{
		global $SITEURL, $PRETTYURLS;
		$blogurl = $this->getSettingsData("blogurl");
		$data = getXML(GSDATAPAGESPATH . $blogurl . '.xml');
		if(function_exists('find_i18n_url')) {
        $url = find_i18n_url($blogurl, $data->parent);
    } else {
        $url = find_url($blogurl, $data->parent);
    }

		if($query) 
		{
			if($query == 'rss')
			{
				$url = $SITEURL.'plugins/'.BLOGFILE.'/rss.php';
			}
			elseif($PRETTYURLS == 1 && $this->getSettingsData("prettyurls") == 'Y')
			{
				$url .= $query . '/';
			}
			elseif($blogurl == 'index')
			{
				$url = $SITEURL . "index.php?$query=";
			}
			else
			{
				$url = $SITEURL . "index.php?id=$blogurl&$query=";
			}
		}
		return $url;
	}

	/** 
	* Creates slug for blog posts
	* 
	* @return string the generated slug
	*/  
	public function blog_create_slug($str) 
	{
		global $i18n;
        if (isset($i18n['TRANSLITERATION']) && is_array($translit=$i18n['TRANSLITERATION']) && count($translit>0))
            {$str = str_replace(array_keys($translit),array_values($translit),$str);}
		$str = to7bit($str, 'UTF-8');
		$str = clean_url($str);
		return $str;
	}

	/** 
	* Gets available blog plugin languages
	* 
	* @return array available languages
	*/  
	public function blog_get_languages() 
	{
		$count = 0;
		foreach(glob(BLOGPLUGINFOLDER."lang/*.php") as $filename)
		{
			$filename = basename(str_replace(".php", "", $filename));
			$languages[$count] = $filename;
			$count++;
		}
		return $languages;
	}

	/** 
	* Create Excerpt for post
	* 
	* @param $content string the content to be excerpted
	* @param $start int the starting character to create excerpt from
	* @param $maxchars int the amount of characters excerpt should be
	* @return string The created excerpt
	*/  
	public function create_excerpt($content, $start, $maxchars)
	{
		$maxchars = (int) $maxchars;
		$content = htmlspecialchars_decode(strip_tags(strip_decode($content)));
		$content = substr($content, $start, $maxchars);
		$pos = strrpos($content, " ");
		if ($pos>0) 
		{
			$content = substr($content, $start, $pos);
		}
		$content = str_replace(i18n_r(BLOGFILE.'/READ_FULL_ARTICLE'), "", $content);
		return $content;
	}

	/** 
	* Gets and sorts archives for blog
	* 
	* @return array archives
	*/  
	public function get_blog_archives() 
	{
		$posts = $this->listPosts();
		$archives = array();
		if(!empty($posts))
		{
			foreach ($posts as $file) 
			{
				$data = getXML($file);
				$date = strtotime($data->date);
        if(strtotime($data->date) <= strtotime(date("d-m-Y H:i:00"))) {
          $title = $this->get_locale_date($date, '%B %Y');
          $archive = date('Ym', $date);
          if (!array_key_exists($archive, $archives))
          {
            $archives[$archive]['title'] = $title;
            $archives[$archive]['count'] = 1;
          }
          else
          {
            $archives[$archive]['count'] = $archives[$archive]['count'] + 1;
          }
        }
			}
			krsort($archives);
		}
		return $archives;
	}

	/** 
	* Generate Search Results
	* 
	* @param $keyphrase string the keyphrase to search for
	* @return array Search results
	*/  
	public function searchPosts( $keyphrase, $filter = array( 'content', 'title' ) )
	{
		$keywords = @explode( ' ', $keyphrase );
		$posts = $this->listPosts();
        $filters = array('title', 'content', 'category' 'author', 'date', 'tags');
		foreach ( $keywords as $keyword )
		{
			$match = array();
			foreach ( $posts as $file )
			{
				$data = getXML( $file );
                
                if ( is_array( $filter ) )
                {
                    foreach ( $filter as $fltr )
                    {
                        if ( in_array($fltr, $filters) &&
                            stripos($data->{$fltr}, $keyword) !== FALSE &&
                            in_array($file, $match) == FALSE )
                        {
                            $match[] = $file;
                        }
                    }
                }
                elseif ( is_string( $filter ) && $filter == 'all')
                {
                    foreach ( $filters as $nodes ) {
                        $results = $this->searchPosts( $keyphrase, $node );
                        $match = array_merge( $match, $results );
                    }
                }
                elseif ( is_string( $filter ) && in_array( $filter, $filters ))
                {
                    if ( stripos( $data->{$filter}, $keyword) !== FALSE )
                    {
                        $match[] = $file;
                    }
                }
                // else { bad $filter parameter given }
			}
		}
		return $match;
	}

    /**
    * Filter Posts (Depreciated Function) - here for backwards compatibility
    * This method is mapped to the `searchPosts` method
    */
    public function filterPosts( $filter, $value ) {
        return $this->searchPosts( $value, $keyphrase );
    }

	/** 
	* get_locale_date
	* @param $timestamp UNIX timestamp
	* @return string date according to lang
	*/  
	public function get_locale_date($timestamp, $format) 
	{
		$locale = setlocale(LC_TIME, NULL);
		setlocale(LC_TIME, i18n_r(BLOGFILE.'/LANGUAGE_CODE'));
    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
      $format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
    }
		$date = strftime($format, $timestamp);
		setlocale(LC_TIME, $locale);
		return $date;
	}

	/** 
	* Generates RSS Feed of posts
	* 
	* @return bool
	*/  
	public function generateRSSFeed($save=true, $filtered=false)
	{
		global $SITEURL;

		$post_array = glob(BLOGPOSTSFOLDER . "/*.xml");
    if($post_array === false){$post_array = array();}
    
		if($save == true)
		{
			$locationOfFeed = $SITEURL."rss.rss";
			$posts = $this->listPosts(true, true);
		}
		else
		{
			$locationOfFeed = $SITEURL."plugins/".BLOGFILE."/rss.php";
			if($filtered != false)
			{
				$posts = $this->filterPosts($filtered['filter'], $filtered['value']);
			}
			else
			{
				$posts = $this->listPosts(true, true);
			}
		}
    
    if($posts === false) {
      $posts = array();
    }

		$RSSString      = "";
		$RSSString     .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$RSSString     .= "<rss version=\"2.0\"  xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
		$RSSString     .= "<channel>\n";
		$RSSString     .= "<title>".$this->getSettingsData("rsstitle")."</title>\n";
		$RSSString     .= "<link>".$locationOfFeed."</link>\n";
		$RSSString     .= "<description>".$this->getSettingsData("rssdescription")."</description>\n";
		$RSSString     .= "<lastBuildDate>".date(DATE_RSS)."</lastBuildDate>\n";
		$RSSString     .= "<language>".BLOGLANGUAGE."</language>\n";
    $RSSString     .= "<generator>GetSimple CMS : GSBlog Plugin (v".BLOGVERSION.")</generator>\n";
		$RSSString     .= '<atom:link href="'.$locationOfFeed."\" rel=\"self\" type=\"application/rss+xml\" />\n";

		$limit = $this->getSettingsData("rssfeedposts");
		array_multisort(array_map('filemtime', $post_array), SORT_DESC, $post_array); 
		$post_array = array_slice($post_array, 0, $limit);
    
    if(!count($posts) < 1) {
      foreach ($posts as $post) 
      {
        $blog_post  = simplexml_load_file($post['filename']);
        $RSSDate    = $blog_post->date;
        $RSSTitle   = $blog_post->title;
        $RSSExcerpt = $this->create_excerpt($blog_post->content,0,$this->getSettingsData('excerptlength'));
        $ID 		    = $blog_post->slug;
        $RSSString .= "<item>\n";
        $RSSString .= "\t<title>".$RSSTitle."</title>\n";
        $RSSString .= "\t<pubDate>".date(DATE_RSS,strtotime($blog_post->date))."</pubDate>\n";
        $RSSString .= "\t<link>".$this->get_blog_url('post').$ID."</link>\n";
        $RSSString .= "\t<guid>".$this->get_blog_url('post').$ID."</guid>\n";
        $RSSString .= "\t<description>".$RSSExcerpt."</description>\n";
        $RSSString .= "\t<content:encoded>".$blog_post->content."</content:encoded>\n";
        if(isset($blog_post->category) and !empty($blog_post->category) and $blog_post->category!='') 
        {
          $RSSString .= "\t  <category>".$blog_post->category."</category>\n";
        }
        $RSSString .= "</item>\n";
      }
    } else {
      $RSSString .= "<item>\n";
      $RSSString .= "\t  <title>There are no posts!</title>\n";
      $RSSString .= "\t  <link>".$this->get_blog_url('post')."</link>\n";
      $RSSString .= "\t  <guid>".$this->get_blog_url('post')."</guid>\n";
      $RSSString .= "\t  <description>There are no posts available for this RSS feed. Please contact the website administrator for more information.</description>\n";
      $RSSString .= "</item>\n";
    }
     
		$RSSString .= "</channel>\n";
		$RSSString .= "</rss>\n";

		if($save==true)
		{
			if(!$fp = fopen(GSROOTPATH."rss.rss",'w'))
			{
				i18n(BLOGFILE.'/RSS_FILE_OPEN_FAIL');
				exit();
			}
			if(!fwrite($fp,$RSSString))
			{
				i18n(BLOGFILE.'/RSS_FILE_WRITE_FAIL');
				exit();
			}
			fclose($fp);
		}
		else
		{
			return $RSSString;
		}
	}

	/** 
	* Creates Blog Posts Cache File
	* 
	* @return bool
	*/  
	public function createPostsCache()
	{
		$posts = $this->listPosts(true, true);
    if($posts === false){$posts = array();}
		$count = 0;
		$xml = new SimpleXMLExtended('<?xml version="1.0"?><item></item>');
    if(!count($posts) < 1) {
      foreach($posts as $post)
      {
        $data = getXML($post['filename']);
        $new_post = $xml->addChild("post");
        foreach($data as $key => $value)
        {
          $post_parent = $new_post->addChild($key);
          $post_parent->addCData($value);
        }
      }
      $save_cache = XMLsave($xml, BLOGCACHEFILE);
    }
	}

	/** 
	* Sorts dates of blog posts (launched through usort function)
	* 
	* @param $a $b array the data to be sorted (from usort)
	* @return bool
	*/  
	public function sortDates($a, $b)
	{
		$a = strtotime($a['date']); 
		$b = strtotime($b['date']); 
		if ($a == $b) 
		{ 
			return 0; 
		} 
		else
		{  
			if($a<$b) 
			{ 
				return 1; 
			} 
			else 
			{ 
				return -1; 
			} 
		} 
	}

	public function regexReplace($content) 
	{
		$the_callback = preg_match('/{\$\s*([a-zA-Z0-9_]+)(\s+[^\$]+)?\s*\$}/', $content, $matches);
		if(isset($matches[0]))
		{
			$display_post_data = str_replace('{$ ', '', $matches[0]);
			$display_post_data = str_replace(' $}', '', $display_post_data);
			echo str_replace($matches[0],$display_post_data,$content);
		}
		else
		{
			return $content;
		}
	}
}
