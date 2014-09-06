<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/** 
* Admin controller
* 
* @return void
*/ 
function blog_admin_controller()
{
	$Blog = new Blog;
	getBlogUserPermissions();
	global $blogUserPermissions;
	if(!isset($_GET['update'])) {
		$update = blog_version_check();
		if($update[0] == 'current') {$ucolor = '#308000';}
		elseif($update[0] == 'update') {$ucolor = '#FFA500';}
		elseif($update[0] == 'beta') {$ucolor = '#2B5CB3';}
		else{$ucolor = '#D94136';}
	} else {$ucolor = '#777777';}

	if(isset($_GET['edit_post']) && $blogUserPermissions['blogeditpost'] == true)
	{
		editPost($_GET['edit_post']);
	}
	elseif(isset($_GET['create_post']) && $blogUserPermissions['blogcreatepost'] == true)
	{
		editPost();
	}
	elseif(isset($_GET['categories']) && $blogUserPermissions['blogcategories'] == true)
	{
		if(isset($_GET['edit_category']))
		{
			$add_category = $Blog->saveCategory($_POST['new_category']);
			if($add_category == true)
			{
				echo '<div class="updated">';
				i18n(BLOGFILE.'/CATEGORY_ADDED');
				echo '</div>';
			}
			else
			{
				echo '<div class="error">';
				i18n(BLOGFILE.'/CATEGORY_ERROR');
				echo '</div>';
			}
		}
		if(isset($_GET['delete_category']))
		{
			$Blog->deleteCategory($_GET['delete_category']);
		}
		edit_categories();
	}
	elseif(isset($_GET['auto_importer']) && $blogUserPermissions['blogrssimporter'] == true)
	{
		if(isset($_POST['post-rss']))
		{
			$post_data = array();
			$post_data['name'] = $_POST['post-rss'];
			$post_data['category'] = $_POST['post-category'];
			$add_feed = $Blog->saveRSS($post_data);
			if($add_feed == true)
			{
				echo '<div class="updated">';
				i18n(BLOGFILE.'/FEED_ADDED');
				echo '</div>';
			}
			else
			{
				echo '<div class="error">';
				i18n(BLOGFILE.'/FEED_ERROR');
				echo '</div>';
			}
		}
		elseif(isset($_GET['delete_rss']))
		{
			$delete_feed = $Blog->deleteRSS($_GET['delete_rss']);
			if($delete_feed == true)
			{
				echo '<div class="updated">';
				i18n(BLOGFILE.'/FEED_DELETED');
				echo '</div>';
			}
			else
			{
				echo '<div class="error">';
				i18n(BLOGFILE.'/FEED_DELETE_ERROR');
				echo '</div>';
			}
		}
		edit_rss();
	}
	elseif(isset($_GET['settings']) && $blogUserPermissions['blogsettings'] == true)
	{
		show_settings_admin();
	}
	elseif(isset($_GET['update']) && $blogUserPermissions['blogsettings'] == true)
	{
		show_update_admin();
	}
	elseif(isset($_GET['help']) && $blogUserPermissions['bloghelp'] == true)
	{
		show_help_admin();
	}
	elseif(isset($_GET['custom_fields']) && $blogUserPermissions['blogcustomfields'] == true)
	{
		$CustomFields = new customFields;
		if(isset($_POST['save_custom_fields']))
		{
			$saveCustomFields = $CustomFields->saveCustomFields();
			if($saveCustomFields)
			{
				echo '<div class="updated">'.i18n_r(BLOGFILE.'/EDIT_OK').'</div>';
			}
		}
		show_custom_fields();
	}
	else
	{
		if(isset($_GET['save_post']))
		{
			savePost();
		}
		elseif(isset($_GET['delete_post']) && $blogUserPermissions['blogdeletepost'] == true)
		{
			$post_id = urldecode($_GET['delete_post']);
			$delete_post = $Blog->deletePost($post_id);
			if($delete_post == true)
			{
				echo '<div class="updated">';
				i18n(BLOGFILE.'/POST_DELETED');
				echo '</div>';
			}
			else
			{
				echo '<div class="error">';
				i18n(BLOGFILE.'/FEED_DELETE_ERROR');
				echo '</div>';
			}
		}
		show_posts_admin();
	}
}

/** 
* Shows blog posts in admin panel
* 
* @return void
*/  
function show_posts_admin()
{
	$Blog = new Blog;
	$all_posts = $Blog->listPosts(true, true);
  ?>
  <h3 class="floated" style="float:left"><?php i18n(BLOGFILE.'/MANAGE_POSTS'); ?></h3>
  <div class="edit-nav">
    <p class="text 1">
      <a href="load.php?id=<?php echo BLOGFILE; ?>&custom_fields"><?php i18n(BLOGFILE.'/CUSTOM_FIELDS_BUTTON'); ?></a>
      <a href="load.php?id=<?php echo BLOGFILE; ?>&create_post"><?php i18n(BLOGFILE.'/NEW_POST_BUTTON'); ?></a>
    </p>
    <div class="clear"></div>
  </div>
  <p class="text 2"><?php i18n(BLOGFILE.'/MANAGE_POSTS_DESC'); ?></p>
  <?php
	if(empty($all_posts))
	{
		echo '<strong>'.i18n_r(BLOGFILE.'/NO_POSTS').'. <a href="load.php?id='.BLOGFILE.'&create_post">'.i18n_r(BLOGFILE.'/CLICK_TO_CREATE').'</a>';
	}
	else
	{
		?>
		<table class="edittable highlight paginate">
			<tr>
				<th><?php i18n(BLOGFILE.'/PAGE_TITLE'); ?></th>
				<th style="text-align:right;" ><?php i18n(BLOGFILE.'/DATE'); ?></th>
				<th></th>
			</tr>
		<?php
		foreach($all_posts as $post_name)
		{
			$post = $Blog->getPostData($post_name['filename']);
			?>
				<tr>
					<td class="blog_post_title"><a title="<?php echo $post->title; ?>" href="load.php?id=<?php echo BLOGFILE; ?>&edit_post=<?php echo $post->slug; ?>" ><?php echo $post->title; ?></a></td>
					<td style="text-align:right;"><span><?php echo $post->date; ?></span></td>
					<td class="delete" ><a class="delconfirm" href="load.php?id=<?php echo BLOGFILE; ?>&delete_post=<?php echo $post->slug; ?>" title="<?php i18n(BLOGFILE.'/DELETE'); ?>: <?php echo $post->title; ?>" >X</a></td>
				</tr>
			<?php
		}
		echo '</table>';
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

/** 
* Edit/Create post screen
* 
* @param $post_id string the id of the post to edit. Null if creating new page
* @return void
*/  
function editPost($post_id=null)
{
	global $SITEURL;
	$Blog = new Blog;
	if($post_id != null)
	{
		$blog_data = getXML(BLOGPOSTSFOLDER.$post_id.'.xml');
	}
	else
	{
		$blog_data = $Blog->getXMLnodes();
	}
	?>
	<link href="../plugins/<?php echo BLOGFILE; ?>/inc/uploader/client/fileuploader.css" rel="stylesheet" type="text/css">
	<script src="../plugins/<?php echo BLOGFILE; ?>/inc/uploader/client/fileuploader.js" type="text/javascript"></script>
	<noscript><style>#metadata_window {display:block !important} </style></noscript>
	<h3 class="floated">
	  <?php
	  if ($post_id == null)
	  {
	  	i18n(BLOGFILE.'/ADD_P');
	  }
	  else
	  {
	  	i18n(BLOGFILE.'/EDIT');
	  }
	  ?>
	</h3>
	<div class="edit-nav" >
		<?php
		if ($post_id != null && file_exists(BLOGPOSTSFOLDER.$blog_data->slug.'.xml')) 
		{
			$url = $Blog->get_blog_url('post');
			?>
			<a href="<?php echo $url.$blog_data->slug; ?>" target="_blank">
				<?php i18n(BLOGFILE.'/VIEW'); echo ' '; i18n(BLOGFILE.'/POST'); ?>
			</a>
			<?php
		}
		?>
		<a href="#" id="metadata_toggle">
			<?php i18n(BLOGFILE.'/POST_OPTIONS'); ?>
		</a>
		<div class="clear"></div>
	</div>
	<form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&save_post" method="post" accept-charset="utf-8">
	<?php if($post_id != null) { echo "<p><input name=\"post-current_slug\" type=\"hidden\" value=\"$blog_data->slug\" /></p>"; } ?>
	<div id="metadata_window" style="display:none;text-align:left;">
		<?php displayCustomFields(); ?>
		<div class="leftopt">
				<label><?php i18n(BLOGFILE.'/UPLOAD_THUMBNAIL'); ?></label>
			<div class="uploader_container"> 
			    <div id="file-uploader-thumbnail"> 
			        <noscript> 
			            <p><?php i18n(BLOGFILE.'/UPLOAD_ENABLE_JAVASCRIPT'); ?></p>
			        </noscript> 
			    </div> 
			    <script> 
			   		 var uploader = new qq.FileUploader({
				        element: document.getElementById('file-uploader-thumbnail'),
				        // path to server-side upload script
				        action: '../plugins/<?php echo BLOGFILE; ?>/inc/uploader/server/php.php',
			        	onComplete: function(id, fileName, responseJSON){
				        	$('#post-thumbnail').attr('value', responseJSON.newFilename);
				    	}

			    }, '<?php i18n(BLOGFILE.'/POST_THUMBNAIL_LABEL'); ?>');
			    </script>
			</div>
			<input type="text" id="post-thumbnail" name="post-thumbnail" value="<?php echo $blog_data->thumbnail; ?>" style="width:130px;float:right;margin-top:12px !important;" />
		</div>
		<div class="clear"></div>
		</div>

		<?php displayCustomFields('main'); ?>
			<input name="post" type="submit" class="submit" value="<?php i18n(BLOGFILE.'/SAVE_POST'); ?>" />
			&nbsp;&nbsp;<?php i18n(BLOGFILE.'/OR'); ?>&nbsp;&nbsp;
			<a href="load.php?id=<?php echo BLOGFILE; ?>&cancel" class="cancel"><?php i18n(BLOGFILE.'/CANCEL'); ?></a>
			<?php
			if ($post_id != null) 
			{
				?>
				/
				<a href="load.php?id=<?php echo BLOGFILE; ?>" class="cancel">
					<?php i18n(BLOGFILE.'/DELETE'); ?>
				</a>
				<?php
			}
			?>
		</p>
	</form>
	<script>
	  $(document).ready(function(){
	    $("#post-title").focus();
	  });
	</script>
	<?php
	include BLOGPLUGINFOLDER."ckeditor.php";
}

/** 
* Show Category management area
* 
* @return void
*/  
function edit_categories()
{
	$Blog = new Blog;
	$category_file = getXML(BLOGCATEGORYFILE);
?>
	<h3 class="floated" style="float:left;"><?php i18n(BLOGFILE.'/MANAGE_CATEGORIES'); ?></h3>
  <div class="edit-nav">
    <p class="text 1">
      <a href="#" id="metadata_toggle"><?php i18n(BLOGFILE.'/ADD_CATEGORY'); ?></a>
    </p>
    <div class="clear"></div>
  </div>
  <p class="text 2"><?php i18n(BLOGFILE.'/SETTINGS_CATEGORY_DESC'); ?></p>
  <div id="metadata_window" style="display:none;text-align:left;">
		<form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&categories&edit_category" method="post">
		    <p style="float:left;width:150px;">
          <label for="page-url"><?php i18n(BLOGFILE.'/ADD_CATEGORY'); ?></label>
          <input class="text" type="text" name="new_category" value="" style="padding-bottom:5px;" />
		    </p>
		    <p style="float:left;width:200px;margin-left:20px;margin-top:8px;">
		    <span>
          <input class="submit" type="submit" name="category_edit" value="<?php i18n(BLOGFILE.'/ADD_CATEGORY'); ?>" style="width:auto;" />
		    </span>
		    &nbsp;&nbsp;<?php i18n(BLOGFILE.'/OR'); ?>&nbsp;&nbsp;
		    <a href="load.php?id=<?php echo BLOGFILE; ?>" class="cancel"><?php i18n(BLOGFILE.'/CANCEL'); ?></a>
		  </p>
		</form>
    <div class="clear"></div>
	</div>
	  <table class="highlight">
	  <tr>
	  <th><?php i18n(BLOGFILE.'/CATEGORY_NAME'); ?></th>
	  <th><?php i18n(BLOGFILE.'/RSS_FEED'); ?></th>
	  <th><?php i18n(BLOGFILE.'/DELETE'); ?></th>
	  </tr>
	  <?php
	foreach($category_file->category as $category)
	{
		?>
		<tr>
			<td><?php echo $category; ?></td>
			<td><a href="<?php echo $Blog->get_blog_url('rss').'?filter=category&value='.$category; ?>" target="_blank"><img src="../plugins/<?php echo BLOGFILE; ?>/images/rss_feed.png" class="rss_feed" /></a></td>
			<td class="delete" ><a href="load.php?id=<?php echo BLOGFILE; ?>&categories&delete_category=<?php echo $category; ?>" title="Delete Category: <?php echo $category; ?>" >X</a></td>
		</tr>
		<?php
	}
	  ?>
	  </table>
	</form>
<?php
}

/** 
* RSS Feed management area
* 
* @return void
*/  
function edit_rss()
{
	  $rss_file = getXML(BLOGRSSFILE);
?>
  <h3 class="floated" style="float:left;"><?php i18n(BLOGFILE.'/RSS_HEADER'); ?></h3>
  <div class="edit-nav">
    <p class="text 1">
      <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=rss"><?php i18n(BLOGFILE.'/SETTINGS_BUTTON'); ?></a>
      <a href="#" id="metadata_toggle"><?php i18n(BLOGFILE.'/ADD_FEED'); ?></a>
    </p>
    <div class="clear"></div>
  </div>
  <p class="text 2"><?php i18n(BLOGFILE.'/SETTINGS_FEED_DESC'); ?></p>
	<div id="metadata_window" style="display:none;text-align:left;">
		<form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&auto_importer&add_rss" method="post">
		    <p style="float:left;width:150px;">
		      <label for="page-url"><?php i18n(BLOGFILE.'/ADD_NEW_FEED'); ?></label>
			  <input class="text" type="text" name="post-rss" value="" style="padding-bottom:5px;" />
		    </p>
		    <p style="float:left;width:100px;margin-left:20px;">
		    	<label for="page-url"><?php i18n(BLOGFILE.'/BLOG_CATEGORY'); ?></label>
				<select class="text" name="post-category">	
					<?php category_dropdown(); ?>
				</select>
		    </p>
		    <p style="float:left;width:200px;margin-left:20px;margin-top:8px;">
		    <span>
		      <input class="submit" type="submit" name="rss_edit" value="<?php i18n(BLOGFILE.'/ADD_FEED'); ?>" style="width:auto;" />
		    </span>
		    &nbsp;&nbsp;<?php i18n(BLOGFILE.'/OR'); ?>&nbsp;&nbsp;
		    <a href="load.php?id=<?php echo BLOGFILE; ?>" class="cancel"><?php i18n(BLOGFILE.'/CANCEL'); ?></a>
		  </p>
		</form>
    <div class="clear"></div>
	</div>
	  <div class="clear"></div>
	  <table class="highlight">
	  <tr>
	  <th><?php i18n(BLOGFILE.'/RSS_FEED'); ?></th><th><?php i18n(BLOGFILE.'/FEED_CATEGORY'); ?></th><th><?php i18n(BLOGFILE.'/DELETE_FEED'); ?></th>
	  </tr>
	  <?php
	foreach($rss_file->rssfeed as $feed)
	{
		$rss_atts = $feed->attributes();
	echo '
	<tr><td>'.$feed->feed.'</td><td>'.$feed->category.'</td><td><a href="load.php?id='.BLOGFILE.'&auto_importer&delete_rss='.$feed['id'].'">X</a></td></tr>
	';
	}
	  ?>
	  </table>
<?php
}

/** 
* Echos all categories to place into select menu
* 
* @return void
*/  
function category_dropdown($current_category=null)
{
	$category_file = getXML(BLOGCATEGORYFILE);	
	foreach($category_file->category as $category_item)	
	{		
		$category_item = (string) $category_item;
		if($category_item == $current_category)
		{
			echo '<option value="'.$current_category.'" selected>'.$current_category.'</option>';	
		}
		else
		{
			echo '<option value="'.$category_item.'">'.$category_item.'</option>';	
		}	
	}	
	if($current_category == null)
	{
		echo '<option value="" selected></option>';	
	}
	else
	{
		echo '<option value=""></option>';	
	}
}

/** 
* Saves A Post
* 
* @return void success or error message
*/  
function savePost()
{
	$Blog = new Blog;
	$xmlNodes = $Blog->getXMLnodes(true);
	if(isset($_POST['post-title']))
	{
		foreach($xmlNodes as $key => $value)
		{
			if(!isset($_POST["post-".$key]))
			{
				$post_value = '';
			}
			else
			{
				$post_value = $_POST["post-".$key];
			}
			$post_data[$key] = $post_value;
		}
		$savePost = $Blog->savePost($post_data);
		$generateRSS = $Blog->generateRSSFeed();
		if($savePost != false)
		{
			echo '<div class="updated">';
			i18n(BLOGFILE.'/POST_ADDED');
			echo '</div>';
		}
		else
		{
			echo '<div class="error">';
			i18n(BLOGFILE.'/POST_ERROR');
			echo '</div>';
		}
	}
	else
	{
		echo '<div class="error">';
		i18n(BLOGFILE.'/BLOG_CREATE_EDIT_NO_TITLE');
		echo ' <a href="javascript:history.go(-1)">'.i18n_r(BLOGFILE.'/BLOG_RETURN_TO_PREVIOUS_PAGE').'</a>';
		echo '</div>';
	}
}

/** 
* Display Plugin Help
* 
* @return void
*/  
function show_help_admin()
{
	global $SITEURL; 
	$Blog = new Blog;
	?>
	<h3>
		<?php i18n(BLOGFILE.'/PLUGIN_TITLE'); ?> <?php i18n(BLOGFILE.'/HELP'); ?>
	</h3>

	<h2 style="font-size:16px;"><?php i18n(BLOGFILE.'/FRONT_END_FUNCTIONS'); ?></h2>
	<p>
		<label><?php i18n(BLOGFILE.'/HELP_CATEGORIES'); ?><?php i18n(BLOGFILE.'/RSS_LOCATION'); ?>:</label>
		<?php highlight_string('<?php show_blog_categories(); ?>'); ?>
	</p>
	<p>
		<label><?php i18n(BLOGFILE.'/HELP_SEARCH'); ?>:</label>
		<?php highlight_string('<?php show_blog_search(); ?>'); ?>
	</p>
	<p>
		<label><?php i18n(BLOGFILE.'/HELP_ARCHIVES'); ?>:</label>
		<?php highlight_string('<?php show_blog_archives(); ?>'); ?>
	</p>
	<p>
		<label><?php i18n(BLOGFILE.'/HELP_RECENT'); ?>:</label>
		<?php highlight_string('<?php show_blog_recent_posts(); ?>'); ?><br/><br/>
		<?php i18n(BLOGFILE.'/HELP_RECENT_2'); ?>: <br/>
		<?php highlight_string('<?php ($excerpt=false, $excerpt_length=null, $thumbnail=null, $read_more=null) ?>'); ?><br/>
		<?php i18n(BLOGFILE.'/HELP_RECENT_3'); ?>:<br/>
		<?php highlight_string('<?php show_blog_recent_posts(true, null, true, true); ?>'); ?><br/>
	</p>
	<p>
		<label><?php i18n(BLOGFILE.'/RSS_LOCATION'); ?> :</label>
		<a href="<?php echo $SITEURL."rss.rss"; ?>" target="_blank"><?php echo $SITEURL."rss.rss"; ?></a>
	</p>
	<p>
		<label><?php i18n(BLOGFILE.'/DYNAMIC_RSS_LOCATION'); ?> :</label>
		<a href="<?php echo $SITEURL."plugins/".BLOGFILE."/rss.php"; ?>" target="_blank"><?php echo $SITEURL."plugins/".BLOGFILE."/rss.php"; ?></a>
	</p>

	<h3><?php i18n(BLOGFILE.'/AUTO_IMPORTER_TITLE'); ?></h3>
	<p>
		<?php i18n(BLOGFILE.'/AUTO_IMPORTER_DESC'); ?>
		<br/>
		<strong>lynx -dump <?php echo $SITEURL; ?>index.php?id=<?php echo $Blog->getSettingsData("blogurl"); ?>&import=<?php echo $Blog->getSettingsData("autoimporterpass"); ?> > /dev/null</strong>
	</p>
	<?php
	blog_page_help_html();
}

/** 
* Display Custom Blog Post Help
* 
* @return void
*/  
function blog_page_help_html()
{
	?>
	<h3><?php i18n(BLOGFILE.'/BLOG_PAGE_DESC_TITLE'); ?></h3>
	<p>
		<strong><?php i18n(BLOGFILE.'/BLOG_PAGE_RECOM'); ?></strong>
	</p><br/>
	<p>
		<strong><?php i18n(BLOGFILE.'/BLOG_PAGE_DESC_LINE_1'); ?></strong> <br/>
		<?php i18n(BLOGFILE.'/BLOG_PAGE_DESC_LINE_2'); ?><br/>
		<span style="text-decoration:underline;"><?php i18n(BLOGFILE.'/BLOG_PAGE_DESC_LINE_3'); ?>:</span> <br/>
		<?php highlight_string('<h1 class="title"><?php echo $post->title; ?></h1>'); ?><br/>
		<?php highlight_string('<p><img src="<?php echo $post->thumbnail; ?>" />'); ?><br/>
		<?php highlight_string('<?php echo $post->content; ?></p>'); ?><br/><br/>
	</p>

	<h3><?php i18n(BLOGFILE.'/BLOG_PAGE_AVAILABLE_FUNCTIONS'); ?></h3>
	<ul>
		<li>
			<strong><?php i18n(BLOGFILE.'/BLOG_PAGE_FORMAT_DATE_LABEL'); ?>: </strong><?php highlight_string('<?php echo formatPostDate($post->date); ?>'); ?><br/>
			<?php i18n(BLOGFILE.'/BLOG_PAGE_FORMAT_DATA_DESC'); ?><br/><br/>
		</li>
		<li>
			<strong><?php i18n(BLOGFILE.'/BLOG_PAGE_GET_URL_TO_AREAS'); ?>: </strong><?php highlight_string('<?php $Blog->get_blog_url(\'post\'); ?>'); ?><br/>
			<strong><?php i18n(BLOGFILE.'/BLOG_PAGE_URL_EX_LABEL'); ?>: </strong> <?php highlight_string('<?php echo $Blog->get_blog_url(\'post\').$post->slug; ?>'); ?><br/>
			<strong><?php i18n(BLOGFILE.'/BLOG_PAGE_AVAILABLE_AREAS'); ?></strong>
			<ul style="margin-left:20px;list-style:disc">
				<li><?php i18n(BLOGFILE.'/BLOG_PAGE_POST'); ?></li>
				<li><?php i18n(BLOGFILE.'/BLOG_PAGE_TAG'); ?></li>
				<li><?php i18n(BLOGFILE.'/BLOG_PAGE_PAGE'); ?></li>
				<li><?php i18n(BLOGFILE.'/BLOG_PAGE_ARCHIVE'); ?></li>
				<li><?php i18n(BLOGFILE.'/BLOG_PAGE_CATEGORY'); ?></li>
			</ul><br/>
		</li>
		<li>
			<strong><?php i18n(BLOGFILE.'/BLOG_PAGE_ADD_THIS'); ?></strong>
			<?php highlight_string('<?php addThisTool(); ?>'); ?>
			<br/><br/>
		</li>
		<li>
			<strong><?php i18n(BLOGFILE.'/BLOG_PAGE_SHARE_THIS'); ?>: </strong>
			<?php highlight_string('<?php shareThisTool(); ?>'); ?>
			<br/><br/>
		</li>
		<li>
			<strong><?php i18n(BLOGFILE.'/BLOG_PAGE_DISQUS_COMMENTS'); ?>: </strong>
			<?php highlight_string('<?php disqusTool(); ?>'); ?>
			<br/><br/>
		</li>
		<li>
			<strong><?php i18n(BLOGFILE.'/BLOG_PAGE_CREATE_EXCERPT'); ?>: </strong><br/>
			<span style="font-size:10px;"><?php highlight_string('<?php echo $Blog->create_excerpt(html_entity_decode($post->content), 0, $excerpt_length); ?>'); ?></span><br/>
			<?php i18n(BLOGFILE.'/BLOG_PAGE_CREATE_EXCERPT_DESC'); ?>
			<br/><br/>
		</li>
		<li>
			<strong><?php i18n(BLOGFILE.'/BLOG_PAGE_DECODE_CONTENT'); ?>: </strong>
			<?php highlight_string('<?php echo htmlspecialchars_decode($post->content); ?>'); ?>
			<br/><br/>
		</li>
		<li>
			<strong><?php i18n(BLOGFILE.'/BLOG_PAGE_ADD_DATA_LABEL'); ?>: </strong>
			<?php highlight_string('<?php echo $Blog->getSettingsData("addata"); ?>'); ?>
			<br/><br/>
		</li>
	</ul>
	<?php
}

function blog_theme_layouts() {
  $files = array_filter(glob(BLOGPLUGINFOLDER.'templates/*.php'), 'is_file');
  $list = array();
  foreach ($files as $file) {
    array_push($list, pathinfo($file, PATHINFO_FILENAME));
  }
  return $list;
}
