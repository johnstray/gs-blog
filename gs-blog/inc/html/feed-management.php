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