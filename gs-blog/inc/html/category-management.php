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