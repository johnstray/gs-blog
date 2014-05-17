<?php
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