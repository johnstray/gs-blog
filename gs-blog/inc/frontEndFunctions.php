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
  
  if(strtotime($post->date) <= time()) {return false;} // Is this a future post?
  
  # Prepare the array of information available to the template.
  $p = array(); // Init the array for the template
  $p['title'] = $post->title; // Title of the post
  $p['posturl'] = $Blog->get_blog_url('post').$post->slug; // URL of the post
  $p['date'] = strtotime($post->date); // UNIX timestamp of post
  $p['author'] = $post->author; //Author of the post
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
  } else {return false;}
  
  # Lets load the template now and let it put all this together.
  $template = (empty($blogSettings['template']) ? 'innovation' : $blogSettings['template']);
  require_once(BLOGPLUGINFOLDER.'templates/'.$template.'.php');
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
 * @return void (void)
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

/** 
* Show posts for requested tag
* 
* @return void
*/  
function show_blog_tag($tag) {
	$Blog = new Blog;
	$all_posts = $Blog->listPosts(true, true);
	foreach ($all_posts as $file) {
		$data = getXML($file['filename']);
		if(!empty($data->tags)) {
			$tags = explode(',', $data->tags);
			if (in_array($tag, $tags)) {
				show_blog_post($file['filename'], true);	
			}
		}
	}
}

/** 
* Show all postts
* 
* @return void
*/  
function show_all_blog_posts()
{
	$Blog = new Blog;
	if(isset($_GET['page']))
	{
		$page = $_GET['page'];
	}
	else
	{
		$page = 0;
	}
	show_posts_page($page);
}

/** 
* Display blog posts results from a search
* 
* @return void
*/  
function search_posts($keyphrase)
{
	$Blog = new Blog;
	$posts = $Blog->searchPosts($keyphrase);
	if (!empty($posts)) 
	{
		echo '<p class="blog_search_header">';
			i18n(BLOGFILE.'/FOUND');
		echo '</p>';
		foreach ($posts as $file)
		{
			show_blog_post($file, TRUE);
		}
	} 
	else 
	{
		echo '<p class="blog_search_header">';
			i18n(BLOGFILE.'/NOT_FOUND');
		echo '</p>';
	}
}

/** 
* Thumbnail download for RSS Auto-Importer
* Finds the first image in $content then downloads
* and saves the image for use as a thumbnail to be
* attached to the imported post 
* 
* @input $item Array containing the RSS data
* @return $thumbnail Filename of the image
* @return false if error or non found
*/ 
function auto_import_thumbnail($item)
{
  // require_once('phpQuery.php');
  return '';
}

/** 
* RSS Feed Auto Importer
* Auto imports RSS feeds. Can be launched by a cron job 
* 
* @return void
*/  
function auto_import()
{
	$Blog = new Blog;
  
	if($_GET['import'] == urldecode($Blog->getSettingsData("autoimporterpass")) && $Blog->getSettingsData("autoimporter") =='Y')
	{
		ini_set("memory_limit","350M");
    
    define('MAGPIE_CACHE_DIR', GSCACHEPATH.'magpierss/');
		require_once(BLOGPLUGINFOLDER.'inc/magpierss/rss_fetch.inc');

		$rss_feed_file = getXML(BLOGRSSFILE);
		foreach($rss_feed_file->rssfeed as $the_fed)
		{
		    $rss_uri = $the_fed->feed;
		    $rss_category = $the_fed->category;
		        
		    $rss = fetch_rss($rss_uri);
		    $items = array_slice($rss->items, 0);
		    foreach ($items as $item )
		    {
		        $post_data['title']         = $item['title'];
		        $post_data['slug']          = '';
		        $post_data['date']          = $item['pubdate'];
		        $post_data['private']       = '';
		        $post_data['tags']          = '';
		        $post_data['category']      = $rss_category;
            
            if($Blog->getSettingsData('rssinclude') == 'Y') {
              if(!empty($item['content']['encoded'])) {
                $post_data['content']   = htmlentities($item['content']['encoded'],ENT_QUOTES,'iso-8859-1');
              } else {
                $post_data['content']   = htmlentities($item['summary'],ENT_QUOTES,'iso-8859-1').'<p class="blog_auto_import_readmore"><a href="'.$item['link'].'" target="_blank">'.i18n_r(BLOGFILE.'/READ_FULL_ARTICLE').'</a></p>';
              }
            } else {
              $post_data['content']     = htmlentities($item['summary'],ENT_QUOTES,'iso-8859-1').'<p class="blog_auto_import_readmore"><a href="'.$item['link'].'" target="_blank">'.i18n_r(BLOGFILE.'/READ_FULL_ARTICLE').'</a></p>';
            }
		        $post_data['excerpt']       = $Blog->create_excerpt($item['summary'],0,$Blog->getSettingsData("excerptlength"));
		        $post_data['thumbnail']     = auto_import_thumbnail($item);
		        $post_data['current_slug']  = '';
            $post_data['author']        = htmlentities('<a href="'.$rss_uri.'">RSS Feed</a>',ENT_QUOTES,'iso-8859-1');

		        $Blog->savePost($post_data, true);
            
            echo '<p class="blog_rss_post_added">Added: '.$post_data['title'].'</p>';
		    }
		}
	}
}

/** 
* RSS Feed Auto Importer
* Auto imports RSS feeds. Can be launched by a cron job 
* 
* @return void
*/  
/*******************************************************
 * @function show_posts_page
 * param $index - page index (pagination)
 * @action show posts on news page
 */
function show_posts_page($index=0) 
{
	global $blogSettings;
	$Blog = new Blog;
	$posts = $Blog->listPosts(true, true);
	if($blogSettings["allpostsadtop"] == 'Y')
	{
		?>
		<div class="blog_all_posts_ad">
			<?php echo $blogSettings["addata"]; ?>
		</div>
		<?php
	}
	if(!empty($posts))
	{
		$pages = array_chunk($posts, intval($blogSettings["postperpage"]), TRUE);
		if (is_numeric($index) && $index >= 0 && $index < sizeof($pages))
		{
			$posts = $pages[$index];
		}
		else
		{
			$posts = array();	
		}
		$count = 0;
		$lastPostOfPage = false;
		foreach ($posts as $file)
		{
			$count++;
			show_blog_post($file['filename'], true);

			if($count == sizeof($posts) && sizeof($posts) > 0) 
			{
				$lastPostOfPage = true;	
			}

			if (sizeof($pages) > 1)
			{
				// We know here that we have more than one page.
				$maxPageIndex = sizeof($pages) - 1;
				show_blog_navigation($index, $maxPageIndex, $count, $lastPostOfPage);
				if($count == $blogSettings["postperpage"])
				{
					$count = 0;
				}
			}
		}
	} 
	else 
	{
		echo '<p>' . i18n(BLOGFILE.'/NO_POSTS') . '</p>';
	}
	if($blogSettings["allpostsadbottom"] == 'Y')
	{
		?>
		<div class="blog_all_posts_ad">
			<?php echo $blogSettings["addata"]; ?>
		</div>
		<?php
	}
}

/** 
* Blog posts navigation (pagination)
* 
* @param $index the current page index
* @param $total total number of pages
* @param $count current post
* @return void
*/  
function show_blog_navigation($index, $total, $count, $lastPostOfPage) 
{
	global $blogSettings;
	$Blog = new Blog;
	$url = $Blog->get_blog_url('page');

	if ($lastPostOfPage) 
	{
		echo '<div class="blog_page_navigation">';
	}
	
	if($index < $total && $lastPostOfPage)
	{
	?>
		<div class="left blog-next-prev-link">
		<a href="<?php echo $url . ($index+1); ?>">
			&larr; <?php echo $blogSettings["nextpage"]; ?>
		</a>
		</div>
	<?php	
	}
	?>
		
	<?php
	if ($index > 0 && $lastPostOfPage)
	{
	?>
		<div class="right blog-next-prev-link">
		<a href="<?php echo ($index > 1) ? $url . ($index-1) : substr($url, 0, -6); ?>">
			<?php echo $blogSettings["previouspage"]; ?> &rarr;
		</a>
		</div>
	<?php
	}
	?>
	
	<?php
	if ($lastPostOfPage) 
	{
		echo '<div id="clear"></div>';
		echo '</div>';
	}

}

/** 
* Get Page/POST Title
* This function is a modified version of the core get_page_clean_title() function. It will function normally on all pages except individual blog posts, where the post title will be placed in instead of the page title.
* 
* @return void
*/  
function get_blog_title($echo=true) 
{
	global $title, $blogSettings, $post;
	$slug = base64_encode(return_page_slug());
	if($slug == base64_encode($blogSettings["blogurl"]))
	{
		if(isset($_GET['post']) && !empty($post))
		{
			$title = (string) $post->title;
		}
	}
	$myVar = strip_tags(strip_decode($title));
	if ($echo) 
	{
		echo $myVar;
	} 
	else 
	{
		return $myVar;
	}
}

function set_post_description()
{
	global $metad, $post, $blogSettings;
	$Blog = new Blog;
	if($blogSettings["postdescription"] == 'Y' && isset($post))
	{
		$excerpt_length = ($blogSettings["excerptlength"] == '') ? 150 : $blogSettings["excerptlength"];

		$metad = $Blog->create_excerpt(html_entity_decode($post->content), 0, $excerpt_length);
	}
}

function set_blog_title () { 
	global $title, $blogSettings, $post;
	$slug = base64_encode(return_page_slug());
	if($slug == base64_encode($blogSettings["blogurl"])) {
		if(isset($_GET['post']) && !empty($post)) {
			$title = (string) $post->title;
		} else if (isset($_GET['archive'])) {
			$title = (string) i18n_r(BLOGFILE.'/ARCHIVE_PRETITLE').date('F Y',strtotime($_GET['archive']));
		} else if (isset($_GET['category'])) {
			$title = (string) i18n_r(BLOGFILE.'/CATEGORY_PRETITLE').$_GET['category'];
		}
	}
	$title = strip_tags(strip_decode($title));
}
