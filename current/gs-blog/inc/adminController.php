<?php
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
	
?>
<div style="width:100%;margin:0 -15px -20px 0;padding:0px;position:relative;">
<div style="position:absolute;width:90px;top:10px;right:5px;font-size:10px;text-align:center;color:#333;line-height:12px;">
	<?php echo i18n_r(BLOGFILE.'/PLUGIN_LONG_TITLE'); ?><br />
	<a style="color:<?php echo $ucolor; ?>;font-weight:normal;text-decoration:none;" href="load.php?id=<?php echo BLOGFILE; ?>&update">Version <?php echo BLOGVERSION; ?></a>
</div>
<?php
	if (isset($_GET['manage'])) { ?>
    <h3 class="floated" style="float:left"><?php i18n(BLOGFILE.'/MANAGE_TITLE'); ?></h3>
    <p class="clear" style="color:#666;"><?php i18n(BLOGFILE.'/MANAGE_DESC'); ?></p>
  <?php } else if (isset($_GET['create_post'])) { ?>
	<h3 class="floated" style="float:left"><?php i18n(BLOGFILE.'/CREATE_TITLE'); ?></h3>
    <p class="clear" style="color:#666;"><?php i18n(BLOGFILE.'/CREATE_DESC'); ?></p>
  <?php } else if (isset($_GET['categories'])) { ?>
	<h3 class="floated" style="float:left"><?php i18n(BLOGFILE.'/CATEGORIES_TITLE'); ?></h3>
    <p class="clear" style="color:#666;"><?php i18n(BLOGFILE.'/CATEGORIES_DESC'); ?></p>
  <?php } else if (isset($_GET['auto_importer'])) { ?>
  	<h3 class="floated" style="float:left"><?php i18n(BLOGFILE.'/RSSFEEDS_TITLE'); ?></h3>
    <p class="clear" style="color:#666;"><?php i18n(BLOGFILE.'/RSSFEEDS_DESC'); ?></p>
  <?php } else if (isset($_GET['custom_fields'])) { ?>
  	<h3 class="floated" style="float:left"><?php i18n(BLOGFILE.'/CUSTF_TITLE'); ?></h3>
    <p class="clear" style="color:#666;"><?php i18n(BLOGFILE.'/CUSTF_DESC'); ?></p>
  <?php } else if (isset($_GET['settings'])) { ?>
  	<h3 class="floated" style="float:left"><?php i18n(BLOGFILE.'/SETTINGS_TITLE'); ?></h3>
    <p class="clear" style="color:#666;"><?php i18n(BLOGFILE.'/SETTINGS_DESC'); ?></p>
  <?php } else if (isset($_GET['help'])) { ?>
  	<h3 class="floated" style="float:left"><?php i18n(BLOGFILE.'/HELP_TITLE'); ?></h3>
    <p class="clear" style="color:#666;"><?php i18n(BLOGFILE.'/HELP_DESC'); ?></p>
  <?php } else if (isset($_GET['update'])) { ?>
  	<h3 class="floated" style="float:left"><?php i18n(BLOGFILE.'/UPDATE_TITLE'); ?></h3>
    <p class="clear" style="color:#666;"><?php i18n(BLOGFILE.'/UPDATE_DESC'); ?></p>
  <?php } else { ?>
	<h3 class="floated" style="float:left"><?php i18n(BLOGFILE.'/PLUGIN_TITLE'); ?></h3>
    <p class="clear" style="color:#666;"><?php i18n(BLOGFILE.'/PLUGIN_DESC'); ?></p>
  <?php } ?>
</div></div>
<div class="main" style="margin-top:-10px;"><?php

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