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
		echo '<strong>'.i18n_r(BLOGFILE.'/NO_POSTS').' <a href="load.php?id='.BLOGFILE.'&create_post">'.i18n_r(BLOGFILE.'/CLICK_TO_CREATE').'</a>.';
	}
	else
	{
		?>
		<table class="edittable highlight paginate">
			<tr>
				<th><?php i18n(BLOGFILE.'/PAGE_TITLE'); ?></th>
				<th><?php i18n(BLOGFILE.'/DATE'); ?></th>
				<th></th>
			</tr>
		<?php
		foreach($all_posts as $post_name)
		{
			$post = $Blog->getPostData($post_name['filename']);
			?>
				<tr>
					<td><a title="<?php echo $post->title; ?>" href="load.php?id=<?php echo BLOGFILE; ?>&edit_post=<?php echo $post->slug; ?>" ><?php echo $post->title; ?></a></td>
					<td><?php $date=(string)$post->date; echo $Blog->get_locale_date(strtotime($date), i18n_r(BLOGFILE.'/DATE_FORMAT')); ?></td>
					<td class="delete"><a class="delconfirm" href="load.php?id=<?php echo BLOGFILE; ?>&delete_post=<?php echo $post->slug; ?>" title="<?php i18n(BLOGFILE.'/DELETE'); ?>: <?php echo $post->title; ?>" >&times;</a></td>
				</tr>
			<?php
		}
		echo '</table>';
	}