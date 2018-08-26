  <h3 class="floated" style="float:left"><?php i18n(BLOGFILE.'/MANAGE_POSTS'); ?></h3>
  <div class="edit-nav">
    <p class="text 1">
      <a href="load.php?id=<?php echo BLOGFILE; ?>&create_post"><?php i18n(BLOGFILE.'/NEW_POST_BUTTON'); ?></a>
      <?php if(!empty($all_posts) && !isset($_GET['filter'])) { ?><a href="#" id="metadata_toggle"><?php i18n(BLOGFILE.'/FILTER_POSTS'); ?></a><?php } ?>
      <?php if(isset($_GET['filter'])) { ?><a href="load.php?id=<?php echo BLOGFILE; ?>"><?php i18n(BLOGFILE.'/CLEAR_FILTERS'); ?></a><?php } ?>
    </p>
    <div class="clear"></div>
  </div>
  <!--<p class="text 2"><?php i18n(BLOGFILE.'/MANAGE_POSTS_DESC'); ?></p>-->
  <style>#metadata_window p {margin: 0 0 10px 0;}</style>
  <?php $search_display = (isset($_GET['search']) || isset($_GET['filter'])) ? 'block' : 'none'; ?>
  <div id="metadata_window" style="display:<?php echo $search_display; ?>;text-align:left;padding:10px 15px;">
    <p style="border-bottom:1px dotted #ccc;margin-bottom: 10px;font-size:11px;line-height:12px;"><strong><?php i18n(BLOGFILE.'/FILTER_POSTS'); ?></strong> : <?php i18n(BLOGFILE.'/FILTER_DESC'); ?></p>
    <form class="largeform" action="load.php" method="get">
      <input type="hidden" name="id" value="<?php echo BLOGFILE; ?>" />
        <select class="text" name="filter" style="height:24px;display:inline;width:35.6%;vertical-align:top;">
          <option value="title"<?php if(isset($_GET['filter']) && $_GET['filter'] == "title") {echo "selected=\"selected\"";} ?>><?php i18n(BLOGFILE.'/POST_TITLE'); ?></option>
          <option value="content"<?php if(isset($_GET['filter']) && $_GET['filter'] == "content") {echo "selected=\"selected\"";} ?>><?php i18n(BLOGFILE.'/POST_CONTENT'); ?></option>
          <option value="category"<?php if(isset($_GET['filter']) && $_GET['filter'] == "category") {echo "selected=\"selected\"";} ?>><?php i18n(BLOGFILE.'/CATEGORY_NAME'); ?></option>
          <option value="author"<?php if(isset($_GET['filter']) && $_GET['filter'] == "author") {echo "selected=\"selected\"";} ?>><?php i18n(BLOGFILE.'/POST_AUTHOR'); ?></option>
          <option value="date"<?php if(isset($_GET['filter']) && $_GET['filter'] == "date") {echo "selected=\"selected\"";} ?>><?php i18n(BLOGFILE.'/PUBLISHED_DATE'); ?> [YYYY-MM-DD]</option>
          <option value="tags"<?php if(isset($_GET['filter']) && $_GET['filter'] == "tags") {echo "selected=\"selected\"";} ?>><?php i18n(BLOGFILE.'/TAG'); ?></option>
          <option disabled>────────────────</option>
          <option value="all"<?php if(isset($_GET['filter']) && $_GET['filter'] == "all") {echo "selected=\"selected\"";} ?>><?php i18n(BLOGFILE.'/ALL_FIELDS'); ?></option>
        </select>
        <input class="text" type="text" name="search" value="<?php if(isset($_GET['search'])) {echo $_GET['search'];} ?>" style="height:16px;width:50%;" />
        <input class="submit" type="submit" name="" value="<?php i18n(BLOGFILE.'/SEARCH'); ?>" style="width:12%;height:24px;padding:3px 10px;vertical-align:top;" />
    </form>
    <div class="clear"></div>
  </div>
  <?php
  if (isset($_GET['search']) && empty($all_posts)) {
    echo '<h4 style="text-align:center;font-weight:bold;margin-bottom:15px;">'.i18n_r(BLOGFILE.'/NO_POSTS_MATCH_SEARCH').'<h4>';
  } elseif (empty($all_posts)) {
		echo '<h5 style="text-align:center;">'.i18n_r(BLOGFILE.'/NO_POSTS').' <a href="load.php?id='.BLOGFILE.'&create_post">'.i18n_r(BLOGFILE.'/CLICK_TO_CREATE').'</a>.</h5>';
	} else {
  ?>
		<div id="tableContainer"><table class="edittable highlight paginate" id="datatable">
			<?php
            foreach($all_posts as $post_name) {
                $post = $Blog->getPostData($post_name['filename']);
			?>
				<tr>
					<td>
                        <img src="<?php if(!empty($post->thumbnail)){echo '../data/uploads/'.$post->thumbnail;}else{echo '../plugins/'.BLOGFILE.'/images/missing.png';} ?>" />
                        <a title="<?php echo $post->title; ?>" href="load.php?id=<?php echo BLOGFILE; ?>&edit_post=<?php echo $post->slug; ?>"><?php echo getExcerpt($post->title,90,false,' ...',false,false); ?></a>
                        <div>
                            <div>Category: <span style="font-size:inherit"><?php echo $post->category; ?></span></div>
                            <div>Author: <span style="font-size:inherit"><?php echo $post->author; ?></span></div>
                            <div>Published: <span style="font-size:inherit"><?php $date=(string)$post->date; echo $Blog->get_locale_date(strtotime($date), i18n_r(BLOGFILE.'/DATE_DISPLAY')); ?></span></div>
                            <div class="clear"></div>
                        </div>
                    </td>
                    <td class="delete"><a class="delpost" href="load.php?id=<?php echo BLOGFILE; ?>&delete_post=<?php echo $post->slug; ?>" title="<?php i18n(BLOGFILE.'/DELETE'); ?>: <?php echo $post->title; ?>" ><i class="fa fa-trash-o"></i></a></td>
				</tr>
			<?php
		}
		echo '</tbody></table></div>';
	} 
  
  if(count($all_posts) >= (int) $Blog->getSettingsData("postperpage")) { ?>

  <div id="metadata_window" style="margin:0;padding:5px;">
    <div style="text-align:center;line-height:23px;">
      <div id="pageNavPosition"></div>
    </div>
    <div class="clear"></div>
  </div>
  
  <script type="text/javascript"><!--
    jQuery(function($) {
       var pageParts = $("#datatable tr"); var numPages = pageParts.length; var perPage = <?php echo $Blog->getSettingsData("postperpage"); ?>;
       sliceTableRows(1, perPage); calcTableHeight(perPage);
       $("#pageNavPosition").pagination({items:numPages, itemsOnPage: perPage, onPageClick: function(pageNum) {sliceTableRows(pageNum,perPage);}});
       $(".delpost").on("click", function ($e) {$e.preventDefault();deletePostAjax($(this),perPage);});
    });
  //--></script>
  <?php } ?>
