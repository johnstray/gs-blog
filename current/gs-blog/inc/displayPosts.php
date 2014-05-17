<?php
function blog_display_posts() 
{
	GLOBAL $content, $blogSettings, $data_index;
	
	$Blog = new Blog;
	$slug = base64_encode(return_page_slug());
	$blogSettings = $Blog->getSettingsData();
	$blog_slug = base64_encode($blogSettings["blogurl"]);
	if($slug == $blog_slug)
	{
		$content = '';
		ob_start();
		if($blogSettings["displaycss"] == 'Y')
		{
			echo "<style>\n";
			echo $blogSettings["csscode"];
			echo "\n</style>";
		}
		switch(true)
		{
			case (isset($_GET['post']) == true) :
				$post_file = BLOGPOSTSFOLDER.$_GET['post'].'.xml';
				show_blog_post($post_file);
				break;
			case (isset($_POST['search_blog']) == true) :
				search_posts($_POST['keyphrase']);
				break;
			case (isset($_GET['archive']) == true) :
				$archive = $_GET['archive'];
				show_blog_archive($archive);
				break;
			case (isset($_GET['tag']) == true) :
				$tag = $_GET['tag'];
				show_blog_tag($tag);
				break;
			case (isset($_GET['category']) == true) :
				$category = $_GET['category'];      
				show_blog_category($category);	
				break;
			case (isset($_GET['import'])) :
				auto_import();
				break;
			default :
				show_all_blog_posts();
				break;
		}
		$content = ob_get_contents();
	    ob_end_clean();		
	}
		return $content; // legacy support for non filter hook calls to this function
}