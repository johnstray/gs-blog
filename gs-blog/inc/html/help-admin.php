	<h3>
		<?php i18n(BLOGFILE.'/PLUGIN_TITLE'); ?> <?php i18n(BLOGFILE.'/HELP'); ?>
	</h3>

	<h2 style="font-size:16px;"><?php i18n(BLOGFILE.'/FRONT_END_FUNCTIONS'); ?></h2>
	<p>
		<label><?php i18n(BLOGFILE.'/HELP_CATEGORIES'); ?>:</label>
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