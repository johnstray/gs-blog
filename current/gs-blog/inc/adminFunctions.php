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
					<td class="blog_post_title"><a title="Edit Page: Agents" href="load.php?id=<?php echo BLOGFILE; ?>&edit_post=<?php echo $post->slug; ?>" ><?php echo $post->title; ?></a></td>
					<td style="text-align:right;"><span><?php echo $post->date; ?></span></td>
					<td class="delete" ><a class="delconfirm" href="load.php?id=<?php echo BLOGFILE; ?>&delete_post=<?php echo $post->slug; ?>" title="Delete Post: <?php echo $post->title; ?>" >X</a></td>
				</tr>
			<?php
		}
		echo '</table>';
	}
}

/** 
* Settings panel for admin area
* 
* @return void
*/  
function show_settings_admin()
{
	global $SITEURL;
	$Blog = new Blog;
	if(isset($_POST['blog_settings']))
	{
		$blog_settings_array = array(
      'blogurl' => (!empty($_POST['blog_url'])) ? $_POST['blog_url'] : $Blog->getSettingsData("blogurl"),
      'lang' => (!empty($_POST['language'])) ? $_POST['language'] : $Blog->getSettingsData("lang"),
      'excerptlength' => (!empty($_POST['excerpt_length'])) ? $_POST['excerpt_length'] : $Blog->getSettingsData("excerptlength"),
      'postformat' => (!empty($_POST['show_excerpt'])) ? $_POST['show_excerpt'] : $Blog->getSettingsData("postformat"),
      'postperpage' => (!empty($_POST['posts_per_page'])) ? $_POST['posts_per_page'] : $Blog->getSettingsData("postperpage"),
      'recentposts' => (!empty($_POST['recent_posts'])) ? $_POST['recent_posts'] : $Blog->getSettingsData("recentposts"),
      'prettyurls' => (!empty($_POST['pretty_urls'])) ? $_POST['pretty_urls'] : $Blog->getSettingsData("prettyurls"),
      'autoimporter' => (!empty($_POST['auto_importer'])) ? $_POST['auto_importer'] : $Blog->getSettingsData("autoimporter"),
      'autoimporterpass' => (!empty($_POST['auto_importer_pass'])) ? $_POST['auto_importer_pass'] : $Blog->getSettingsData("autoimporterpass"),
      'displaytags' => (!empty($_POST['show_tags'])) ? $_POST['show_tags'] : $Blog->getSettingsData("displaytags"),
      'rsstitle' => (!empty($_POST['rss_title'])) ? $_POST['rss_title'] : $Blog->getSettingsData("rsstitle"),
      'rssinclude' => (!empty($_POST['rss_include'])) ? $_POST['rss_include'] : $Blog->getSettingsData("rssinclude"),
      'rssdescription' => (!empty($_POST['rss_description'])) ? $_POST['rss_description'] : $Blog->getSettingsData("rssdescription"),
      'comments' => (!empty($_POST['comments'])) ? $_POST['comments'] : $Blog->getSettingsData("comments"),
      'disqusshortname' => (!empty($_POST['disqus_shortname'])) ? $_POST['disqus_shortname'] : $Blog->getSettingsData("disqusshortname"),
      'disquscount' => (!empty($_POST['disqus_count'])) ? $_POST['disqus_count'] : $Blog->getSettingsData("disquscount"),
      'sharethis' => (!empty($_POST['sharethis'])) ? $_POST['sharethis'] : $Blog->getSettingsData("sharethis"),
      'sharethisid' => (!empty($_POST['sharethis_id'])) ? $_POST['sharethis_id'] : $Blog->getSettingsData("sharethisid"),
      'addthis' => (!empty($_POST['addthis'])) ? $_POST['addthis'] : $Blog->getSettingsData("addthis"),
      'addthisid' => (!empty($_POST['addthis_id'])) ? $_POST['addthis_id'] : $Blog->getSettingsData("addthisid"),
      'addata' => (!empty($_POST['ad_data'])) ? $_POST['ad_data'] : $Blog->getSettingsData("addata"),
      'allpostsadtop' => (!empty($_POST['all_posts_ad_top'])) ? $_POST['all_posts_ad_top'] : $Blog->getSettingsData("allpostsadtop"),
      'allpostsadbottom' => (!empty($_POST['all_posts_ad_bottom'])) ? $_POST['all_posts_ad_bottom'] : $Blog->getSettingsData("allpostsadbottom"),
      'postadtop' => (!empty($_POST['post_ad_top'])) ? $_POST['post_ad_top'] : $Blog->getSettingsData("postadtop"),
      'postadbottom' => (!empty($_POST['post_ad_bottom'])) ? $_POST['post_ad_bottom'] : $Blog->getSettingsData("postadbottom"),
      'postthumbnail' => (!empty($_POST['post_thumbnail'])) ? $_POST['post_thumbnail'] : $Blog->getSettingsData("postthumbnail"),
      'displaydate' => (!empty($_POST['display_date'])) ? $_POST['display_date'] : $Blog->getSettingsData("displaydate"),
      'displayauthor' => (!empty($_POST['display_author'])) ? $_POST['display_author'] : $Blog->getSettingsData("displayauthor"),
      'defaultauthor' => (!empty($_POST['default_author'])) ? $_POST['default_author'] : $Blog->getSettingsData("defaultauthor"),
      'displaycategory' => (!empty($_POST['display_category'])) ? $_POST['display_category'] : $Blog->getSettingsData("displaycategory"),
      'previouspage' => (!empty($_POST['previous_page'])) ? $_POST['previous_page'] : $Blog->getSettingsData("previouspage"),
      'nextpage' => (!empty($_POST['next_page'])) ? $_POST['next_page'] : $Blog->getSettingsData("nextpage"),
      'displaycss' => (!empty($_POST['display_css'])) ? $_POST['display_css'] : $Blog->getSettingsData("displaycss"),
      'csscode' => (!empty($_POST['css_code'])) ? $_POST['css_code'] : $Blog->getSettingsData("csscode"),
      'rssfeedposts' => (!empty($_POST['rss_feed_num_posts'])) ? $_POST['rss_feed_num_posts'] : $Blog->getSettingsData("rssfeedposts"),
      'customfields' => (!empty($_POST['custom_fields'])) ? $_POST['custom_fields'] : $Blog->getSettingsData("customfields"),
      'blogpage' => (!empty($_POST['blog_page'])) ? $_POST['blog_page'] : $Blog->getSettingsData("blogpage"),
      'displayreadmore' => (!empty($_POST['display_read_more'])) ? $_POST['display_read_more'] : $Blog->getSettingsData("displayreadmore"),
      'readmore' => (!empty($_POST['read_more_text'])) ? $_POST['read_more_text'] : $Blog->getSettingsData("readmore"),
      'archivepostcount' => (!empty($_POST['display_archives_post_count'])) ? $_POST['display_archives_post_count'] : $Blog->getSettingsData("archivespostcount"),
      'postdescription' => (!empty($_POST['post_description'])) ? $_POST['post_description'] : $Blog->getSettingsData("postdescription")
    );
		if($Blog->saveSettings($blog_settings_array)) {
      echo '<script>clearNotify();notifyOk(\'Successfully saved settings!\').popit().removeit();</script>';
    } else {
      echo '<script>clearNotify();notifyError(\'Could not save settings!\').popit().removeit();</script>';
    }
	}
  if(($_GET['settings'] == NULL) || ($_GET['settings'] == 'main')) {
    ?>
    <h3 class="floated" style="float:left;"><?php i18n(BLOGFILE.'/BLOG_SETTINGS'); ?></h3>
    <div class="edit-nav">
      <p class="text 1">
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=social"><?php i18n(BLOGFILE.'/SOCIAL_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=advertisement"><?php i18n(BLOGFILE.'/ADVERTISEMENT_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=customise"><?php i18n(BLOGFILE.'/CUSTOMIZE_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=main" class="current"><?php i18n(BLOGFILE.'/SETTINGS_BUTTON'); ?></a>
      </p>
      <div class="clear"></div>
    </div>
    <p class="text 2"><?php i18n(BLOGFILE.'/SETTINGS_MAIN_DESC'); ?></p>
    <form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&settings" method="post" accept-charset="utf-8">
      <div class="leftsec">
          <p>
            <label for="page-url"><?php i18n(BLOGFILE.'/PAGE_URL'); ?>:</label>
            <select class="text" name="blog_url">
              <?php
              $pages = get_available_pages();
              foreach ($pages as $page) 
              {
                $slug = $page['slug'];
                if ($slug == $Blog->getSettingsData("blogurl"))
                {
                  echo "<option value=\"$slug\" selected=\"selected\">$slug</option>\n";
                }
                else
                {
                  echo "<option value=\"$slug\">$slug</option>\n";	
                }
              }
              ?>
            </select>
          </p>
        </div>
        <div class="rightsec">
          <p>&nbsp;</p>
        </div>
        <div class="clear"></div>
        <div class="leftsec">
          <p>
            <label for="show_excerpt"><?php i18n(BLOGFILE.'/EXCERPT_OPTION'); ?>:</label>
            <input name="show_excerpt" type="radio" value="Y" <?php if ($Blog->getSettingsData("postformat") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/FULL_TEXT'); ?>
            <span style="margin-left: 30px;">&nbsp;</span>
            <input name="show_excerpt" type="radio" value="N" <?php if ($Blog->getSettingsData("postformat") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/EXCERPT'); ?>
          </p>
        </div>
        <div class="rightsec">
          <p>
            <label for="excerpt_length"><?php i18n(BLOGFILE.'/EXCERPT_LENGTH'); ?>:</label>
            <input class="text" type="text" name="excerpt_length" value="<?php echo $Blog->getSettingsData("excerptlength"); ?>" />
          </p>
        </div>
        <div class="clear"></div>
        <div class="leftsec">
          <p>
            <label for="posts_per_page"><?php i18n(BLOGFILE.'/POSTS_PER_PAGE'); ?>:</label>
            <input class="text" type="text" name="posts_per_page" value="<?php echo $Blog->getSettingsData("postperpage"); ?>" />
          </p>
        </div>
        <div class="rightsec">
          <p>
            <label for="recent_posts"><?php i18n(BLOGFILE.'/RECENT_POSTS'); ?>:</label>
            <input class="text" type="text" name="recent_posts" value="<?php echo $Blog->getSettingsData("recentposts"); ?>" />
          </p>
        </div>
        <div class="clear"></div>
        <div class="leftsec">
          <p>
            <label for="posts_per_page"><?php i18n(BLOGFILE.'/DISPLAY_TAGS_UNDER_POST'); ?>:</label>
            <input name="show_tags" type="radio" value="Y" <?php if ($Blog->getSettingsData("displaytags") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
            <span style="margin-left: 30px;">&nbsp;</span>
            <input name="show_tags" type="radio" value="N" <?php if ($Blog->getSettingsData("displaytags") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
          </p>
        </div>
        <div class="rightsec">
          <p>
            <label for="post_thumbnail"><?php i18n(BLOGFILE.'/POST_THUMBNAIL'); ?>:</label>
            <input name="post_thumbnail" type="radio" value="Y" <?php if ($Blog->getSettingsData("postthumbnail") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
            <span style="margin-left: 30px;">&nbsp;</span>
            <input name="post_thumbnail" type="radio" value="N" <?php if ($Blog->getSettingsData("postthumbnail") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
          </p>
        </div>
        <div class="clear"></div>
        <div class="leftsec">
          <p>
            <label for="display_date"><?php i18n(BLOGFILE.'/DISPLAY_DATE'); ?>:</label>
            <input name="display_date" type="radio" value="Y" <?php if ($Blog->getSettingsData("displaydate") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
            <span style="margin-left: 30px;">&nbsp;</span>
            <input name="display_date" type="radio" value="N" <?php if ($Blog->getSettingsData("displaydate") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
          </p>
        </div>
        <div class="rightsec">
          <p>
            <label for="display_archives_post_count"><?php i18n(BLOGFILE.'/DISPLAY_POST_COUNT_ARCHIVES'); ?>:</label>
            <input name="display_archives_post_count" type="radio" value="Y" <?php if ($Blog->getSettingsData("archivepostcount") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
            <span style="margin-left: 30px;">&nbsp;</span>
            <input name="display_archives_post_count" type="radio" value="N" <?php if ($Blog->getSettingsData("archivepostcount") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
          </p>
        </div>
        <div class="clear"></div>
        <div class="leftsec">
          <p>
            <label for="display_date"><?php i18n(BLOGFILE.'/DISPLAY_POST_AUTHOR'); ?>:</label>
            <input name="display_author" type="radio" value="Y" <?php if ($Blog->getSettingsData("displayauthor") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
            <span style="margin-left: 30px;">&nbsp;</span>
            <input name="display_author" type="radio" value="N" <?php if ($Blog->getSettingsData("displayauthor") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
          </p>
        </div>
        <div class="rightsec">
          <p>
            <label for="recent_posts"><?php i18n(BLOGFILE.'/DEFAULT_AUTHOR'); ?>:</label>
            <input class="text" type="text" name="default_author" value="<?php echo $Blog->getSettingsData("defaultauthor"); ?>" />
          </p>
        </div>
        <div class="clear"></div>
        <div class="leftsec">
          <p>
            <label for="display_category"><?php i18n(BLOGFILE.'/DISPLAY_POST_CATEGORY'); ?>:</label>
            <input name="display_category" type="radio" value="Y" <?php if ($Blog->getSettingsData("displaycategory") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
            <span style="margin-left: 30px;">&nbsp;</span>
            <input name="display_category" type="radio" value="N" <?php if ($Blog->getSettingsData("displaycategory") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
          </p>
        </div>
        <div class="clear"></div>
        <h3 style="font-size:15px;"><?php i18n(BLOGFILE.'/HTACCESS_HEADLINE'); ?></h3>
        <?php global $PRETTYURLS; if ($PRETTYURLS == 1) { ?>
        <p>
          <label for="pretty_urls"><?php i18n(BLOGFILE.'/PRETTY_URLS'); ?>:</label>
          <input name="pretty_urls" type="radio" value="Y" <?php if ($Blog->getSettingsData("prettyurls") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
          &nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
          <span style="margin-left: 30px;">&nbsp;</span>
          <input name="pretty_urls" type="radio" value="N" <?php if ($Blog->getSettingsData("prettyurls") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
          &nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
        </p>
        <p>
          <span style="color:red;font-weight:bold;"><a id="see_htaccess" href="#htaccess"><?php i18n(BLOGFILE.'/VIEW_HTACCESS'); ?></a></span> - 
          <span class="hint"><?php i18n(BLOGFILE.'/PRETTY_URLS_PARA'); ?></span>
        </p>
				<div style="display:none;">
				<div id="htaccess">
					<pre>
AddDefaultCharset UTF-8
Options -Indexes

# blocks direct access to the XML files - they hold all the data!
&lt;Files ~ "\.xml$"&gt;
    Order allow,deny
    Deny from all
    Satisfy All
&lt;/Files&gt;
&lt;Files sitemap.xml&gt;
    Order allow,deny
    Allow from all
    Satisfy All
&lt;/Files&gt;

RewriteEngine on

# Usually RewriteBase is just '/', but
# replace it with your subdirectory path -- IMPORTANT -> if your site is located in subfolder you need to change this to reflect (eg: /subfolder/)
RewriteBase /

RewriteRule ^<?php if($Blog->getSettingsData("blogurl") != 'index') { echo $Blog->getSettingsData("blogurl").'/'; } ?>post/([^/.]+)/?$ index.php?id=<?php echo $Blog->getSettingsData("blogurl"); ?>&post=$1 [L]
RewriteRule ^<?php if($Blog->getSettingsData("blogurl") != 'index') { echo $Blog->getSettingsData("blogurl").'/'; } ?>tag/([^/.]+)/?$ index.php?id=<?php echo $Blog->getSettingsData("blogurl"); ?>&tag=$1 [L]
RewriteRule ^<?php if($Blog->getSettingsData("blogurl") != 'index') { echo $Blog->getSettingsData("blogurl").'/'; } ?>page/([^/.]+)/?$ index.php?id=<?php echo $Blog->getSettingsData("blogurl"); ?>&page=$1 [L]
RewriteRule ^<?php if($Blog->getSettingsData("blogurl") != 'index') { echo $Blog->getSettingsData("blogurl").'/'; } ?>archive/([^/.]+)/?$ index.php?id=<?php echo $Blog->getSettingsData("blogurl"); ?>&archive=$1 [L]
RewriteRule ^<?php if($Blog->getSettingsData("blogurl") != 'index') { echo $Blog->getSettingsData("blogurl").'/'; } ?>category/([^/.]+)/?$ index.php?id=<?php echo $Blog->getSettingsData("blogurl"); ?>&category=$1 [L]

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule /?([A-Za-z0-9_-]+)/?$ index.php?id=$1 [QSA,L]
					</pre>
				</div>
			</div>
			<?php 
		 } 
		 else
		 {
		 	echo '<p>'.i18n_r(BLOGFILE.'/BLOG_PRETTY_NOTICE').'.</p>';
		 }
		 ?>
        <div style="margin-top:20px;">
          <span><input class="submit" type="submit" name="blog_settings" value="<?php i18n(BLOGFILE.'/SAVE_SETTINGS'); ?>" /></span>
          &nbsp;&nbsp;<?php i18n(BLOGFILE.'/OR'); ?>&nbsp;&nbsp;
          <a href="load.php?id=<?php echo BLOGFILE; ?>&cancel" class="cancel"><?php i18n(BLOGFILE.'/CANCEL'); ?></a>
        </div>
      </form>
      <script type="text/javascript">
        $("a#css_help").fancybox({
          'hideOnContentClick': true
        });
        $("a#blog_page_help").fancybox({
          'hideOnContentClick': true
        });
        $("a#see_htaccess").fancybox({
          'hideOnContentClick': true
        });
      </script>
    <?php
  } elseif ($_GET['settings'] == 'customise') {
    ?>
    <h3 class="floated" style="float:left;"><?php i18n(BLOGFILE.'/CUSTOMIZE_HEADER'); ?></h3>
    <div class="edit-nav">
      <p class="text 1">
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=social"><?php i18n(BLOGFILE.'/SOCIAL_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=advertisement"><?php i18n(BLOGFILE.'/ADVERTISEMENT_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=customise" class="current"><?php i18n(BLOGFILE.'/CUSTOMIZE_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=main"><?php i18n(BLOGFILE.'/SETTINGS_BUTTON'); ?></a>
      </p>
      <div class="clear"></div>
    </div>
    <p class="text 2"><?php i18n(BLOGFILE.'/SETTINGS_CUSTOMIZE_DESC'); ?></p>
    <form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&settings" method="post" accept-charset="utf-8">
      <div class="leftsec">
        <p>
          <label for="display_read_more"><?php i18n(BLOGFILE.'/DISPLAY_READ_MORE_LINK'); ?>:</label>
          <input name="display_read_more" type="radio" value="Y" <?php if ($Blog->getSettingsData("displayreadmore") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
          &nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
          <span style="margin-left: 30px;">&nbsp;</span>
          <input name="display_read_more" type="radio" value="N" <?php if ($Blog->getSettingsData("displayreadmore") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
          &nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
        </p>
      </div>
      <div class="rightsec">
        <p>
          <label for="read_more_text"><?php i18n(BLOGFILE.'/READ_MORE_LINK_TEXT'); ?>:</label>
          <input class="text" type="text" name="read_more_text" value="<?php echo $Blog->getSettingsData("readmore"); ?>" />
        </p>
      </div>
      <div class="clear"></div>
      <div class="leftsec">
        <p>
          <label for="post_description"><?php i18n(BLOGFILE.'/POST_DESCRIPTION'); ?>:</label>
          <input name="post_description" type="radio" value="Y" <?php if ($Blog->getSettingsData("postdescription") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
          &nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
          <span style="margin-left: 30px;">&nbsp;</span>
          <input name="post_description" type="radio" value="N" <?php if ($Blog->getSettingsData("postdescription") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
          &nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
        </p>
      </div>
      <div class="clear"></div>
      <div class="leftsec">
        <p>
          <label for="previous_page"><?php i18n(BLOGFILE.'/PREVIOUS_PAGE_TEXT'); ?>:</label>
          <input class="text" type="text" name="previous_page" value="<?php echo $Blog->getSettingsData("previouspage"); ?>" />
        </p>
      </div>
      <div class="rightsec">
        <p>
          <label for="next_page"><?php i18n(BLOGFILE.'/NEXT_PAGE_TEXT'); ?>:</label>
          <input class="text" type="text" name="next_page" value="<?php echo $Blog->getSettingsData("nextpage"); ?>" />
        </p>
      </div>
      <div class="clear"></div>
      <h3 style="font-size:15px;"><?php i18n(BLOGFILE.'/CUSTOM_PAGE_LAYOUT_HEADER'); ?></h3>
      <p class="text 2"><?php i18n(BLOGFILE.'/BLOG_PAGE_RECOM'); ?>
      <div class="leftsec">
			<p>
				<label for="custom_fields"><?php i18n(BLOGFILE.'/USE_CUSTOM_BLOG_PAGE'); ?>:</label>
				<input name="custom_fields" type="radio" value="Y" <?php if ($Blog->getSettingsData("customfields") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
				<span style="margin-left: 30px;">&nbsp;</span>
				<input name="custom_fields" type="radio" value="N" <?php if ($Blog->getSettingsData("customfields") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
			</p>
		</div>
		<div class="clear"></div>
		<div class="leftec" style="width:100%">
			<p>
				<label for="ad_data"><?php i18n(BLOGFILE.'/BLOG_PAGE'); ?>: <span style="color:red;font-size:15px;"><?php i18n(BLOGFILE.'/BLOG_PAGE_WARNING'); ?></span></label>
				<label for="display_css"><a id="blog_page_help" href="#blog_page_help_data"><?php i18n(BLOGFILE.'/DISPLAY_BLOG_PAGE_HELP'); ?></a></label>
				<div style="display:none;">
					<div id="blog_page_help_data">
						<?php blog_page_help_html(); ?>
					</div>
				</div>
				<textarea name="blog_page" id="blog_page" style=""><?php echo $Blog->getSettingsData("blogpage"); ?></textarea>
			</p>
		</div>
		<div class="clear"></div>
    <h3 style="font-size:15px;"><?php i18n(BLOGFILE.'/CUSTOM_CSS_CLASS'); ?></h3>
		<div class="leftsec">
			<p>
				<label for="display_css"><?php i18n(BLOGFILE.'/DISPLAY_CSS'); ?>: <a id="css_help" href="#css_data"><?php i18n(BLOGFILE.'/VIEW_AVAILABLE_CLASSES'); ?></a></label>
				<div style="display:none;">
					<div id="css_data">
						<h3><?php i18n(BLOGFILE.'/AVAILABLE_CLASSES'); ?></h3>
						<ul>
							<li>.blog_post_container (<?php i18n(BLOGFILE.'/CSS_POST_CONTAINER_HINT'); ?>)</li>
							<li>.blog_post_title</li>
							<li>.blog_post_date</li>
							<li>.blog_post_content (<?php i18n(BLOGFILE.'/CSS_POST_CONTENT_HINT'); ?>)</li>
							<li>.blog_tags</li>
							<li>.blog_page_navigation</li>
							<li>.blog_prev_page</li>
							<li>.blog_next_page</li>
							<li>.blog_go_back</li>
							<li>.blog_search_button</li>
							<li>.blog_search_input</li>
							<li>.blog_search_header</li>
							<li>#disqus_thread</li>
							<li>#blog_search (<?php i18n(BLOGFILE.'/CSS_SEARCH_FORM_HINT'); ?>)</li>
						</ul><br/>
						<h3><?php i18n(BLOGFILE.'/EXAMPLE_POST_HEADER'); ?></h3>
<pre>
&lt;div class=&quot;blog_post_container&quot;&gt;<br />
	&lt;h3 class=&quot;blog_post_title&quot;&gt;&lt;a href=&quot;http://link&quot; class=&quot;blog_post_link&quot;&gt;The Post Title&lt;/a&gt;&lt;/h3&gt;<br />
	&lt;p class=&quot;blog_post_date&quot;&gt;<br />
		May 22, 2012			<br />
	&lt;/p&gt;<br />
	&lt;p class=&quot;blog_post_content&quot;&gt;<br />
		&lt;img src=&quot;http://michaelhenken.com/plugin_tests/blog/data/uploads/math-fail-pics-421.jpg&quot; style=&quot;&quot; class=&quot;blog_post_thumbnail&quot; /&gt;<br />
		An essential part of programming is evaluating conditions using if/else and switch/case statements. If / Else statements are easy to code and..	<br />
	&lt;/p&gt;<br />
&lt;/div&gt;<br />
&lt;p class=&quot;blog_tags&quot;&gt;<br />
	&lt;b&gt;Tags :&lt;/b&gt; <br />
	&lt;a href=&quot;http://link&quot;&gt;tags1&lt;/a&gt; &lt;a href=&quot;http://link&quot;&gt;tags2&lt;/a&gt;<br />
&lt;/p&gt;<br />
&lt;div class=&quot;blog_page_navigation&quot;&gt;		<br />
	&lt;div class=&quot;blog_prev_page&quot;&gt;<br />
		&lt;a href=&quot;http://link&quot;&gt;<br />
		&amp;larr; Older Posts		&lt;/a&gt;<br />
	&lt;/div&gt;<br />
	&lt;div class=&quot;blog_next_page&quot;&gt;<br />
		&lt;a href=&quot;http://link&quot;&gt;<br />
			Newer Posts &amp;rarr;<br />
		&lt;/a&gt;<br />
	&lt;/div&gt;<br />
&lt;/div&gt;
</pre>
				</div>
			</div>
				<script>
			      var editor = CodeMirror.fromTextArea(document.getElementById("blog_page"), {
			        lineNumbers: true,
			        matchBrackets: true,
			        mode: "application/x-httpd-php",
			        indentUnit: 4,
			        indentWithTabs: true,
			        enterMode: "keep",
			        tabMode: "shift",
			        lineWrapping: "true"
			      });
			    </script>

				<input name="display_css" type="radio" value="Y" <?php if ($Blog->getSettingsData("displaycss") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
				<span style="margin-left: 30px;">&nbsp;</span>
				<input name="display_css" type="radio" value="N" <?php if ($Blog->getSettingsData("displaycss") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
			</p>
		</div>
		<div class="clear"></div>
		<div class="leftec" style="width:100%">
			<p>
				<label for="css_code"><?php i18n(BLOGFILE.'/CSS_CODE'); ?>:</label>
				<textarea name="css_code" class="text"  style="width:100%;height:100px;"><?php echo $Blog->getSettingsData("csscode"); ?></textarea>
			</p>
		</div>
		<div class="clear"></div>
      <div style="margin-top:20px;">
        <span><input class="submit" type="submit" name="blog_settings" value="<?php i18n(BLOGFILE.'/SAVE_SETTINGS'); ?>" /></span>
        &nbsp;&nbsp;<?php i18n(BLOGFILE.'/OR'); ?>&nbsp;&nbsp;
        <a href="load.php?id=<?php echo BLOGFILE; ?>&cancel" class="cancel"><?php i18n(BLOGFILE.'/CANCEL'); ?></a>
      </div>
    </form>
    <?php
  } elseif ($_GET['settings'] == 'advertisement') {
    // Advertisment Settings ?>
    <h3 class="floated" style="float:left;"><?php i18n(BLOGFILE.'/ADVERTISEMENT_HEADER'); ?></h3>
    <div class="edit-nav">
      <p class="text 1">
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=social"><?php i18n(BLOGFILE.'/SOCIAL_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=advertisement" class="current"><?php i18n(BLOGFILE.'/ADVERTISEMENT_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=customise"><?php i18n(BLOGFILE.'/CUSTOMIZE_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=main"><?php i18n(BLOGFILE.'/SETTINGS_BUTTON'); ?></a>
      </p>
      <div class="clear"></div>
    </div>
    <p class="text 2"><?php i18n(BLOGFILE.'/SETTINGS_ADVERTISEMENT_DESC'); ?></p>
    <form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&settings" method="post" accept-charset="utf-8">
    <div class="leftsec">
			<p>
				<label for="all_posts_ad_top"><?php i18n(BLOGFILE.'/DISPLAY_ALL_POSTS_AD_TOP'); ?>:</label>
				<input name="all_posts_ad_top" type="radio" value="Y" <?php if ($Blog->getSettingsData("allpostsadtop") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
				<span style="margin-left: 30px;">&nbsp;</span>
				<input name="all_posts_ad_top" type="radio" value="N" <?php if ($Blog->getSettingsData("allpostsadtop") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
			</p>
		</div>
		<div class="rightsec">
			<p>
				<label for="all_posts_ad_bottom"><?php i18n(BLOGFILE.'/DISPLAY_ALL_POSTS_AD_BOTTOM'); ?>:</label>
				<input name="all_posts_ad_bottom" type="radio" value="Y" <?php if ($Blog->getSettingsData("allpostsadbottom") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
				<span style="margin-left: 30px;">&nbsp;</span>
				<input name="all_posts_ad_bottom" type="radio" value="N" <?php if ($Blog->getSettingsData("allpostsadbottom") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
			</p>
		</div>
		<div class="clear"></div>
		<div class="leftsec">
			<p>
				<label for="post_ad_top"><?php i18n(BLOGFILE.'/DISPLAY_POST_AD_TOP'); ?>:</label>
				<input name="post_ad_top" type="radio" value="Y" <?php if ($Blog->getSettingsData("postadtop") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
				<span style="margin-left: 30px;">&nbsp;</span>
				<input name="post_ad_top" type="radio" value="N" <?php if ($Blog->getSettingsData("postadtop") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
			</p>
		</div>
		<div class="rightsec">
			<p>
				<label for="post_ad_bottom"><?php i18n(BLOGFILE.'/DISPLAY_POST_AD_BOTTOM'); ?>:</label>
				<input name="post_ad_bottom" type="radio" value="Y" <?php if ($Blog->getSettingsData("postadbottom") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
				<span style="margin-left: 30px;">&nbsp;</span>
				<input name="post_ad_bottom" type="radio" value="N" <?php if ($Blog->getSettingsData("postadbottom") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
			</p>
		</div>
		<div class="clear"></div>
		<div class="leftec" style="width:100%">
			<p>
				<label for="ad_data"><?php i18n(BLOGFILE.'/AD_DATA'); ?>:</label>
				<textarea name="ad_data" class="text"  style="width:100%;height:100px;"><?php echo $Blog->getSettingsData("addata"); ?></textarea>
			</p>
		</div>
		<div class="clear"></div>
    <div style="margin-top:20px;">
        <span><input class="submit" type="submit" name="blog_settings" value="<?php i18n(BLOGFILE.'/SAVE_SETTINGS'); ?>" /></span>
        &nbsp;&nbsp;<?php i18n(BLOGFILE.'/OR'); ?>&nbsp;&nbsp;
        <a href="load.php?id=<?php echo BLOGFILE; ?>&cancel" class="cancel"><?php i18n(BLOGFILE.'/CANCEL'); ?></a>
      </div>
    </form>
    <?php
  } elseif ($_GET['settings'] == 'social') {
    // Social Settings ?>
    <h3 class="floated" style="float:left;"><?php i18n(BLOGFILE.'/SOCIAL_HEADER'); ?></h3>
    <div class="edit-nav">
      <p class="text 1">
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=social" class="current"><?php i18n(BLOGFILE.'/SOCIAL_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=advertisement"><?php i18n(BLOGFILE.'/ADVERTISEMENT_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=customise"><?php i18n(BLOGFILE.'/CUSTOMIZE_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=main"><?php i18n(BLOGFILE.'/SETTINGS_BUTTON'); ?></a>
      </p>
      <div class="clear"></div>
    </div>
    <p class="text 2"><?php i18n(BLOGFILE.'/SETTINGS_SOCIAL_DESC'); ?></p>
    <form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&settings" method="post" accept-charset="utf-8">
      <div class="leftsec">
			<p>
				<label for="comments"><?php i18n(BLOGFILE.'/DISPLAY_DISQUS_COMMENTS'); ?>:</label>
				<input name="comments" type="radio" value="Y" <?php if ($Blog->getSettingsData("comments") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
				<span style="margin-left: 30px;">&nbsp;</span>
				<input name="comments" type="radio" value="N" <?php if ($Blog->getSettingsData("comments") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
			</p>
		</div>
		<div class="rightsec">
			<p>
				<label for="disqus_shortname"><?php i18n(BLOGFILE.'/DISQUS_SHORTNAME'); ?>:</label>
				<input class="text" type="text" name="disqus_shortname" value="<?php echo $Blog->getSettingsData("disqusshortname"); ?>" />
			</p>
		</div>
		<div class="clear"></div>
		<div class="leftsec">
			<p>
				<label for="posts_per_page"><?php i18n(BLOGFILE.'/DISPLAY_DISQUS_COUNT'); ?>:</label>
				<input name="disqus_count" type="radio" value="Y" <?php if ($Blog->getSettingsData("disquscount") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
				<span style="margin-left: 30px;">&nbsp;</span>
				<input name="disqus_count" type="radio" value="N" <?php if ($Blog->getSettingsData("disquscount") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
			</p>
		</div>
		<div class="clear"></div>
		<div class="leftsec">
			<p>
				<label for="sharethis"><?php i18n(BLOGFILE.'/ENABLE_SHARE_THIS'); ?>:</label>
				<input name="sharethis" type="radio" value="Y" <?php if ($Blog->getSettingsData("sharethis") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
				<span style="margin-left: 30px;">&nbsp;</span>
				<input name="sharethis" type="radio" value="N" <?php if ($Blog->getSettingsData("sharethis") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
			</p>
		</div>
		<div class="rightsec">
			<p>
				<label for="sharethis_id"><?php i18n(BLOGFILE.'/SHARE_THIS_ID'); ?>:</label>
				<input class="text" type="text" name="sharethis_id" value="<?php echo $Blog->getSettingsData("sharethisid"); ?>" />
			</p>
		</div>
		<div class="clear"></div>
		<div class="leftsec">
			<p>
				<label for="addthis"><?php i18n(BLOGFILE.'/ENABLE_ADD_THIS'); ?>:</label>
				<input name="addthis" type="radio" value="Y" <?php if ($Blog->getSettingsData("addthis") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
				<span style="margin-left: 30px;">&nbsp;</span>
				<input name="addthis" type="radio" value="N" <?php if ($Blog->getSettingsData("addthis") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
				&nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
			</p>
		</div>
		<div class="rightsec">
			<p>
				<label for="addthis_id"><?php i18n(BLOGFILE.'/ADD_THIS_ID'); ?>:</label>
				<input class="text" type="text" name="addthis_id" value="<?php echo $Blog->getSettingsData("addthisid"); ?>" />
			</p>
		</div>
      <div class="clear"></div>
      <div style="margin-top:20px;">
        <span><input class="submit" type="submit" name="blog_settings" value="<?php i18n(BLOGFILE.'/SAVE_SETTINGS'); ?>" /></span>
        &nbsp;&nbsp;<?php i18n(BLOGFILE.'/OR'); ?>&nbsp;&nbsp;
        <a href="load.php?id=<?php echo BLOGFILE; ?>&cancel" class="cancel"><?php i18n(BLOGFILE.'/CANCEL'); ?></a>
      </div>
    </form>
    <?php
  } elseif ($_GET['settings'] == 'rss') {
    ?>
    <h3 class="floated" style="float:left;"><?php i18n(BLOGFILE.'/RSS_SETTINGS_HEADER'); ?></h3>
    <div class="edit-nav">
      <p class="text 1">
        <a href="load.php?id=<?php echo BLOGFILE; ?>&settings=main"><?php i18n(BLOGFILE.'/MAIN_SETTINGS_BUTTON'); ?></a>
      </p>
      <div class="clear"></div>
    </div>
    <p class="text 2"><?php i18n(BLOGFILE.'/SETTINGS_RSS_DESC'); ?></p>
    <form class="largeform" action="load.php?id=<?php echo BLOGFILE; ?>&settings" method="post" accept-charset="utf-8">
      <div class="leftsec">
          <p>
            <label for="auto_importer"><?php i18n(BLOGFILE.'/RSS_IMPORTER'); ?>:</label>
            <input name="auto_importer" type="radio" value="Y" <?php if ($Blog->getSettingsData("autoimporter") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/YES'); ?>
            <span style="margin-left: 30px;">&nbsp;</span>
            <input name="auto_importer" type="radio" value="N" <?php if ($Blog->getSettingsData("autoimporter") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/NO'); ?>
          </p>
        </div>
        <div class="rightsec">
          <p>
            <label for="rss_include"><?php i18n(BLOGFILE.'/RSS_CONTENT_DESCRIPTION'); ?>:</label>
            <input name="rss_include" type="radio" value="Y" <?php if ($Blog->getSettingsData("rssinclude") == 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/RSS_CONTENT'); ?>
            <span style="margin-left: 30px;">&nbsp;</span>
            <input name="rss_include" type="radio" value="N" <?php if ($Blog->getSettingsData("rssinclude") != 'Y') echo 'checked="checked"'; ?> style="vertical-align: middle;" />
            &nbsp;<?php i18n(BLOGFILE.'/RSS_DESCRIPTION'); ?>
          </p>
        </div>
        <div class="clear"></div>
        <div class="leftsec">
          <p>
            <label for="rss_title"><?php i18n(BLOGFILE.'/RSS_TITLE'); ?>:</label>
            <input class="text" type="text" name="rss_title" value="<?php echo $Blog->getSettingsData("rsstitle"); ?>" />
          </p>
        </div>
        <div class="rightsec">
          <p>
            <label for="rss_description"><?php i18n(BLOGFILE.'/RSS_DESCRIPTION'); ?>:</label>
            <input class="text" type="text" name="rss_description" value="<?php echo $Blog->getSettingsData("rssdescription"); ?>" />
          </p>
        </div>
        <div class="clear"></div>
        <div class="leftsec">
          <p>
            <label for="recent_posts"><?php i18n(BLOGFILE.'/RSS_IMPORTER_PASS'); ?>:</label>
            <input class="text" type="text" name="auto_importer_pass" value="<?php echo $Blog->getSettingsData("autoimporterpass"); ?>" />
          </p>
        </div>
        <div class="rightsec">
          <p>
            <label for="rss_feed_num_posts"><?php i18n(BLOGFILE.'/RSS_FEED_NUM_POSTS'); ?>:</label>
            <input class="text" type="text" name="rss_feed_num_posts" value="<?php echo $Blog->getSettingsData("rssfeedposts"); ?>" />
          </p>
        </div>
        <div class="clear"></div>
        <p><?php i18n(BLOGFILE.'/AUTO_IMPORTER_DESC'); ?></p>
        <code><strong>lynx -dump <?php echo $SITEURL; ?>index.php?id=<?php echo $Blog->getSettingsData("blogurl"); ?>&import=<?php echo $Blog->getSettingsData("autoimporterpass"); ?> > /dev/null</strong></code>
        <div style="margin-top:20px;">
          <span><input class="submit" type="submit" name="blog_settings" value="<?php i18n(BLOGFILE.'/SAVE_SETTINGS'); ?>" /></span>
          &nbsp;&nbsp;<?php i18n(BLOGFILE.'/OR'); ?>&nbsp;&nbsp;
          <a href="load.php?id=<?php echo BLOGFILE; ?>&cancel" class="cancel"><?php i18n(BLOGFILE.'/CANCEL'); ?></a>
        </div>
      </form>
    <?php
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
					<?php category_dropdown($blog_data->category); ?>
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
