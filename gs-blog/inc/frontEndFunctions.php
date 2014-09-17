<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * @file: frontEndFunctions.php
 * @package: GetSimple Blog [plugin]
 * @action: This file contains all the functions required for displaying the blog.
 * @author: John Stray [https://www.johnstray.id.au/]
 */


/**-------------------------------------------------------------------------------------------------
 * blog_display_posts($slug, $excerpt) 
 * Displays the posts required for the relevant page.
 * 
 * @return:  $content (buffer) - Legacy support for non filter hook calls to this function
 */
function blog_display_posts() {
	
  GLOBAL $content, $blogSettings, $data_index; // Declare GLOBAL variables
	$Blog = new Blog; // Create a new instance of the Blog class
	$slug = base64_encode(return_page_slug()); // Determine the page we are on.
	$blogSettings = $Blog->getSettingsData(); // Get the blog's settings
	$blog_slug = base64_encode($blogSettings["blogurl"]); // What page should the blog show on?
	
  if($slug == $blog_slug) { // If we are on the page that should be showing the blog...
		$content = ''; // Legacy support
		ob_start(); // Create a buffer to load everything into
		switch(true) { // Ok, so what are we going to do?
			case (isset($_GET['post']) == true) : // Display a post
				$post_file = BLOGPOSTSFOLDER.$_GET['post'].'.xml'; // Get the post's XML file
				show_blog_post($post_file); // Show the post
				break;
			case (isset($_POST['search_blog']) == true) : // Search the blog
				search_posts($_POST['keyphrase']); // Search the blog with the given keyphrase
				break;
			case (isset($_GET['archive']) == true) : // View the archives
				$archive = $_GET['archive']; // @TODO: Redundant? Revision required...
				show_blog_archive($archive); // Show the requested archive
				break;
			case (isset($_GET['tag']) == true) : // Show specific post by tag
				$tag = $_GET['tag']; // @TODO: Redundant? Revision required...
				show_blog_tag($tag); // Show all posts that have the given tag
				break;
			case (isset($_GET['category']) == true) : // Show a category of posts
				$category = $_GET['category']; // @TODO: Redundant? Revision required...
				show_blog_category($category); // Show posts from the requested category
				break;
			case (isset($_GET['import'])) : // RSS Auto-Importer
				auto_import(); // Let the RSS Auto-Importer do its thing
				break;
			default : // None of the above?
				show_all_blog_posts(); // Lets just show all the posts then
				break;
		}
		$content = ob_get_contents(); // Legacy support
	  ob_end_clean();	// Output the buffer to the user.
	}
  return $content; // legacy support for non filter hook calls to this function
}

/**-------------------------------------------------------------------------------------------------
 * show_blog_post($slug, $excerpt) 
 * Shows an individual blog post chosen by $slug
 * 
 * @param:   $slug (string)    - Slug of post to display
 * @param:   $excerpt (bool)   - True:  Display excerpt of post
 *                               False: Display full content of post
 * @return:  $displayed (bool) - True:  Post was in the past (displayed)
 *                               False: Post was in the future (not displayed)
 */
function show_blog_post($slug, $excerpt=false) {
  
  GLOBAL $SITEURL, $blogSettings, $post; // Get GLOBAL variables
  $Blog = new Blog; // Prepare the Blog class
  $post = getXML($slug); // Get XML data of post
  
  if(strtotime($post->date) >= time()) {return false;} // Is this a future post?
  
  # Prepare the array of information available to the template.
  $p = array(); // Init the array for the template
  $p['title'] = (string) $post->title; // Title of the post
  $p['posturl'] = $Blog->get_blog_url('post').$post->slug; // URL of the post
  $p['date'] = strtotime($post->date); // UNIX timestamp of post
  $p['author'] = (string) $post->author; //Author of the post
  $p['categoryurl'] = $Blog->get_blog_url('category'); // Category base URL
  $p['categories'] = explode(',',$post->category); // Categories the post is in
  $p['thumburl'] = $SITEURL.'data/uploads/'.$post->thumbnail; // Thumbnail URL
  $p['tagsurl'] = $Blog->get_blog_url('tag'); // Tags base URL
  $p['tags'] = explode(',',$post->tags); // Tags applied to the post

  # Determine if we should be showing an excerpt or full post.
  if(($excerpt == false) || (($excerpt == true) && ($blogSettings['postformat'] == 'Y'))) {
    $p['content'] = html_entity_decode($post->content); // Get the full contents of the post
  } elseif(($excerpt == true) && ($blogSettings['postformat'] == 'N')) { // It's an excerpt...
    $el = (empty($blogSettings['excerptlength']) ? 250 : $blogSettings['excerptlength']); // Length?
    $p['content'] = $Blog->create_excerpt(html_entity_decode($post->content),0,$el); // Create excerpt
  } else {echo 'Uh oh! Something went wrong!';}
  
  # Lets load the template now and let it put all this together.
  $template = (empty($blogSettings['template']) ? 'original' : $blogSettings['template']);
  include(BLOGPLUGINFOLDER.'templates/'.$template.'.php');
}

/**-------------------------------------------------------------------------------------------------
 * show_blog_categories()
 * Shows a list of blog categories
 * 
 * @return void (void)
 */  
function show_blog_categories() {
	
  $Blog = new Blog; // Create a new instance of the Blog class
	$categories = getXML(BLOGCATEGORYFILE); // Get the list of categories
	$url = $Blog->get_blog_url('category'); // What's the URL for the categories page?
	$main_url = $Blog->get_blog_url(); // The base URL for the blog.
  
	if(!empty($categories)) { // If we have categories to display...
		foreach($categories as $category) { // For each of the categories...
			// Output a list item with a link to the category
      echo '<li><a href="'.$url.$category.'">'.$category.'</a></li>';
		}
	} else { // We have no categories
		echo "<li>".i18n(BLOGFILE.'/NO_CATEGORIES')."</li>"; // Let the user know
	}
}

/**-------------------------------------------------------------------------------------------------
 * show_blog_category($category)
 * Shows posts from a requested category
 * 
 * @param $category (string) the category to show posts from
 * @return void (void)
 */  
function show_blog_category($category) {

	$Blog = new Blog; // Create a new instance of the Blog class
	$all_posts = $Blog->listPosts(true, true); // Get a list of all the posts in the blog
	$count = 0; // Set a counter for the following loop
  
	foreach($all_posts as $file) { // For each post in the list...
		$data = getXML($file['filename']); // Get the XML data of the post
		if($data->category == $category || empty($category)) { // Is the post in the requested category?
			$count++; // Increase the counter.
			show_blog_post($file['filename'], true); // Show the blog post
		}
	}
	if($count < 1) { // Counter is still 0? We have no posts in this category.
		echo '<p class="blog_category_noposts">'.i18n_r(BLOGFILE.'/NO_POSTS').'</p>';
	}
}

/**-------------------------------------------------------------------------------------------------
 * show_blog_search()
 * Shows a form for searching the blog.
 * 
 * @return void (void)
 * @TODO: Maybe this could be moved to the template now?
 */  
function show_blog_search() {
	
  $Blog = new Blog; // Create a new instance of the Blog class
	$url = $Blog->get_blog_url(); // Get the base URL of the blog
  
  # Output the search form
	?><form id="blog_search" action="<?php echo $url; ?>" method="post">
		<input type="text" class="blog_search_input" name="keyphrase" />
		<input type="submit" class="blog_search_button" name="search_blog" value="<?php i18n(BLOGFILE.'/SEARCH'); ?>" />
	</form><?php
}

/**-------------------------------------------------------------------------------------------------
 * show_blog_archives()
 * Shows a list of Archives
 * 
 * @return void (void)
 */  
function show_blog_archives() {

	GLOBAL $blogSettings; // Define GLOBAL variables
	$Blog = new Blog; // Create a new instance of the Blog class
	$archives = $Blog->get_blog_archives(); // Get a list of archives.
  
	if (!empty($archives)) { // If we there are any archives in the list...
    foreach ($archives as $archive => $archive_data) { // For each archive in the list...
      // How many posts are there in this archive?
			$post_count = ($blogSettings['archivepostcount'] == 'Y') ? ' ('.$archive_data['count'].')' : '';
			$url = $Blog->get_blog_url('archive') . $archive; // What's the URL for this archive?
			echo "<li><a href=\"{$url}\">{$archive_data['title']} {$post_count}</a></li>"; // Ouput the HTML list item
		}
	} else { // We have no archives in the list
		echo i18n(BLOGFILE.'/NO_ARCHIVES'); // Let the user know
	}
}

/**-------------------------------------------------------------------------------------------------
 * show_blog_archive($archive)
 * Show Posts from requested archive
 * 
 * @param  $archive (string) - Show posts from this given archive
 * @return void     (void)
 */  
function show_blog_archive($archive) {

	$Blog = new Blog; // Create a new instance of the Blog class
	$posts = $Blog->listPosts(true, true); // Get a list of all the posts in the blog
  
	if (!empty($posts)) { // If there are posts in the blog...
		foreach ($posts as $file) { // For each post in the list...
			$data = getXML($file['filename']); // Get the XML data of the post
			$date = strtotime($data->date); // Covert the date to a UNIX timestamp
			if (date('Ym', $date) == $archive) { // If the date on the post is in the requested archive...
				show_blog_post($file['filename'], true); // Show the blog post
			}
		}
	} else { // We have no posts in this archive
		echo i18n(BLOGFILE.'/NO_ARCHIVES'); // Let the user know
	}
}

/**-------------------------------------------------------------------------------------------------
 * show_blog_recent_posts($excerpt, $excerpt_length, $thumbnail, $readmore)
 * Shows a list of recent posts formatted based on arguments given.
 *
 * @param $excerpt        (bool)   True: Show excerpt - False: Title only
 * @param $excerpt_length (int)    Length of excerpt. Default if not given
 * @param $thumbnail      (bool)   If true a thumbnail will be displayed for each post
 * @param $read_more      (string) A text string to show for "Read More" link. Not shown if null
 * @return string or void
 */
function show_blog_recent_posts($excerpt=false, $excerpt_length=null, $thumbnail=null, $read_more=null) {
	
  GLOBAL $SITEURL, $blogSettings; // Declare GLOBAL variables
  $Blog = new Blog; // Create new instance of Blog class
	$posts = $Blog->listPosts(true, true); // Get a list of posts
	
  if (!empty($posts)) { // If we have any posts to display
		$posts = array_slice($posts, 0, $blogSettings["recentposts"], TRUE); // Shorten list to setting
		foreach ($posts as $file) {
			$data = getXML($file['filename']); // Get the XML data of the post
			$url = $Blog->get_blog_url('post') . $data->slug; // Create the URL for the post
			$title = strip_tags(strip_decode($data->title)); // Sanitize the posts title.
      if($excerpt != false) { // If we are showing the excerpt...
				if($excerpt_length == null) { // If the excerpt length was not provided...
					$excerpt_length = $blogSettings["excerptlength"]; // Get excerpt length from settings.
				}
				$excerpt = $Blog->create_excerpt(html_entity_decode($data->content), 0, $excerpt_length); // Create the excerpt
				if($thumbnail != null) { // If we are showing a thumbnail with it...
					if(!empty($data->thumbnail)) { // Does a thumbnail exist with the post?
            // Output the HTML for the image
						$excerpt = '<img src="'.$SITEURL.'data/uploads/'.$data->thumbnail.'" class="blog_recent_posts_thumbnail" />'.$excerpt;
					}
				}
				if($read_more != null) { // Do we want the "Read More" link to show?
          // Show the "Read More" link with the string given in argument
					$excerpt = $excerpt.'<br/><a href="'.$url.'" class="recent_posts_read_more">'.$read_more.'</a>';
				}
        // Output the HTML for the list item with excerpt
				echo '<li><a href="'.$url.'">'.$title.'</a><p class="blog_recent_posts_excerpt">'.$excerpt.'</p></li>';
			} else {
        // Output the HTML for the list item without the excerpt
				echo '<li><a href="'.$url.'">'.$title.'</a></li>';
			}
		}
	}
}

/**-------------------------------------------------------------------------------------------------
 * show_blog_tag($tag)
 * Show posts for requested tag
 * 
 * @param  $tag (string) - Display posts containing this tag
 * @return void (void)
 */  
function show_blog_tag($tag) {

	$Blog = new Blog; // Create a new instance of the Blog class
	$all_posts = $Blog->listPosts(true, true); // Get a list of all posts in the blog
  
	foreach ($all_posts as $file) { // For each blog post in the list...
		$data = getXML($file['filename']); // Get the XML data for the post
		if(!empty($data->tags)) { // If the post has been tagged...
			$tags = explode(',', $data->tags); // Convert the string of tags to array list
			if (in_array($tag, $tags)) { //If the requested tag is in the list of tags on the post
				show_blog_post($file['filename'], true); // Show the blog post
			}
		}
	}
}

/**-------------------------------------------------------------------------------------------------
 * show_all_blog_posts()
 * Show all posts in the blog. Include pagination.
 * 
 * @return void (void)
 */  
function show_all_blog_posts() {

	$Blog = new Blog; // Create a new instance of the Blog class
	if(isset($_GET['page'])) { // If a specific page is required...
		$page = $_GET['page']; // What page do we want?
	} else { // No page given?
		$page = 0; // Default to the first page
	}
	show_posts_page($page); // Show the page of posts
}

/**-------------------------------------------------------------------------------------------------
 * search_posts($keyphrase)
 * Display blog posts results from a search
 * 
 * @param  $keyphrase (string) - The phrase to search for in the posts
 * @return void       (void)
 */  
function search_posts($keyphrase) {

	$Blog = new Blog; // Create new instance of the Blog class
	$posts = $Blog->searchPosts($keyphrase); // Get the list of search results
  
	if (!empty($posts)) { // If there were search results returned...
		echo '<p class="blog_search_header">'.i18n(BLOGFILE.'/FOUND').'</p>'; // Output the page title
		foreach ($posts as $file) { // For each result in the list...
			show_blog_post($file, TRUE); // Show the blog post
		}
	} else { // There were no results to display. Let the user know.
		echo '<p class="blog_search_header">'.i18n(BLOGFILE.'/NOT_FOUND').'</p>';
	}
}
 
/**-------------------------------------------------------------------------------------------------
 * show_posts_page($index)
 * Show all posts for the given page.
 * 
 * @param  $index (int)  - page index (pagination)
 * @return void   (void)
 */
function show_posts_page($index=0) {

	GLOBAL $blogSettings; // Declare GLOBAL variables
	$Blog = new Blog; // Create a new instance of the Blog class
	$posts = $Blog->listPosts(true, true); // Get the list of posts.
	if(!empty($posts)) { // If we have posts to display...
		$pages = array_chunk($posts, intval($blogSettings["postperpage"]), TRUE); // Split posts onto multiple pages
		if (is_numeric($index) && $index >= 0 && $index < sizeof($pages)) { // What page should we show?
			$posts = $pages[$index]; // Show specified page number
		} else { // Page index not given or 0
			$posts = array();	// Show first page
		}
		$count = 0; // Create a counter for X
		$lastPostOfPage = false; // We're not on the last post of the page yet
		foreach ($posts as $file) { // For each post on the page...
			$count++; // Increment the counter
			show_blog_post($file['filename'], true); // Show the blog post
      if($count == sizeof($posts) && sizeof($posts) > 0) { // Is this the last post on the page?
				$lastPostOfPage = true;	// Yes, it is.
			}
      if (sizeof($pages) > 1) { // If there is more than one page...
				$maxPageIndex = sizeof($pages) - 1; // Total number of pages
				show_blog_navigation($index, $maxPageIndex, $count, $lastPostOfPage); // Show the pagination
				if($count == $blogSettings["postperpage"]) { // If we are on the last post,
					$count = 0; // Reset the counter for the next page
				}
			}
		}
	} else { // We have no posts to display. Let the user know.
		echo '<p>' . i18n(BLOGFILE.'/NO_POSTS') . '</p>';
	}
}

/**-------------------------------------------------------------------------------------------------
 * Blog posts navigation (pagination)
 * 
 * @param $index          (int)  the current page index
 * @param $total          (int)  total number of pages
 * @param $count          (int)  current post
 * @param $lastPostOfPage (bool) 
 * @return void           (void) 
 */  
function show_blog_navigation($index, $total, $count, $lastPostOfPage) {
	
  GLOBAL $blogSettings; // Declare GLOBAL variables
	$Blog = new Blog; // Create a new instance of the Blog class
	$url = $Blog->get_blog_url('page'); // Get the "page" URL

	if ($lastPostOfPage) { // Only show navigation if we've past the last post on the page
		echo '<div class="blog_page_navigation">';
	}
	if($index < $total && $lastPostOfPage) { // Generate "Next Page" link
	  ?>
      <div class="left blog-next-prev-link">
        <a href="<?php echo $url . ($index+1); ?>">
          &larr; <?php echo $blogSettings["nextpage"]; ?>
        </a>
      </div>
	  <?php	
	}
	if ($index > 0 && $lastPostOfPage) { // Generate "Previous Page" link
    ?>
      <div class="right blog-next-prev-link">
        <a href="<?php echo ($index > 1) ? $url . ($index-1) : substr($url, 0, -6); ?>">
          <?php echo $blogSettings["previouspage"]; ?> &rarr;
        </a>
      </div>
    <?php
	}
	if ($lastPostOfPage) { // Close off the navigation
		echo '<div id="clear"></div>';
		echo '</div>';
	}
}

/**-------------------------------------------------------------------------------------------------
 * get_blog_title($echo)
 * Get Page/POST Title - This function is a modified version of the core get_page_clean_title()
 * function. It will function normally on all pages except individual blog posts, where the post
 * title will be placed in instead of the page title.
 * 
 * @param  $echo  (bool)   True: Echos output - False: Returns output
 * @return $myVar (string) The post title
 * @TODO: Redundancy check required. Is this a duplicate of set_blog_title()?
 */  
function get_blog_title($echo=true) {
	
  GLOBAL $title, $blogSettings, $post; // Declare GLOBAL variables
	$slug = base64_encode(return_page_slug()); // Get the current pages slug
  
	if($slug == base64_encode($blogSettings["blogurl"])) { // If we are on the blog's page...
		if(isset($_GET['post']) && !empty($post)) { // If we are showing a post...
			$title = (string) $post->title; // Set the page title to title of post
		}
	}
	$myVar = strip_tags(strip_decode($title)); // Clean the title variable
  if($echo){echo $myVar;}else{return $myVar;} // Echo or return as per argument
}

/**-------------------------------------------------------------------------------------------------
 * set_post_description()
 * Sets the pages meta description to an excerpt of the post
 * 
 * @return void (void)
 */
function set_post_description() {

	GLOBAL $metad, $post, $blogSettings; // Declare GLOBAL variables
	$Blog = new Blog; // Create a new instance of the Blog class
  
	if((isset($_GET['id'])) && ($_GET['id'] == $blogSettings['blogurl']) && (isset($_GET['post']))) {
		$excerpt_length = ($blogSettings["excerptlength"] == '') ? 150 : $blogSettings["excerptlength"];
		$metad = $Blog->create_excerpt(html_entity_decode($post->content), 0, $excerpt_length);
	}
}

/**-------------------------------------------------------------------------------------------------
 * set_blog_title()
 * Sets the page title to the title of the current post, the archive name or category name being viewed
 * 
 * @return void (void)
 * @TODO: Redundancy check required. Is this a duplicate of get_blog_title()?
 */
function set_blog_title () {

	GLOBAL $title, $blogSettings, $post; // Declare GLOBAL variables
	$slug = base64_encode(return_page_slug()); // What page are we on?
  
	if($slug == base64_encode($blogSettings["blogurl"])) { // If we're on the blog page...
		if(isset($_GET['post']) && !empty($post)) { // If viewing a post...
			$title = (string) $post->title; // Set title of post
		} else if (isset($_GET['archive'])) { // If viewing an archive...
      // Set the archive title
			$title = (string) i18n_r(BLOGFILE.'/ARCHIVE_PRETITLE').date('F Y',strtotime($_GET['archive']));
		} else if (isset($_GET['category'])) { // If viewing a category...
			$title = (string) i18n_r(BLOGFILE.'/CATEGORY_PRETITLE').$_GET['category']; // Set category title
		}
	}
	$title = strip_tags(strip_decode($title)); // Clean the title variable
}
