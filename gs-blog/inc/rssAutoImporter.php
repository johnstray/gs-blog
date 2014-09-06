<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * @file: rssAutoImporter.php
 * @package: GetSimple Blog [plugin]
 * @action: This file contains all the functions required for the RSS Auto-Importer.
 * @author: John Stray [https://www.johnstray.id.au/]
 */
 
/**-------------------------------------------------------------------------------------------------
 * auto_import()
 * Auto imports RSS feeds. Can be launched by a cron job 
 * 
 * @return void (void)
 */  
function auto_import()
{
	$Blog = new Blog;
  
	if($_GET['import'] == urldecode($Blog->getSettingsData("autoimporterpass")) && $Blog->getSettingsData("autoimporter") =='Y')
	{
		ini_set("memory_limit","350M");
    
    define('MAGPIE_CACHE_DIR', GSCACHEPATH.'magpierss/');
		require_once(BLOGPLUGINFOLDER.'inc/magpierss/rss_fetch.inc');

		$rss_feed_file = getXML(BLOGRSSFILE);
		foreach($rss_feed_file->rssfeed as $the_fed)
		{
		    $rss_uri = $the_fed->feed;
		    $rss_category = $the_fed->category;
		        
		    $rss = fetch_rss($rss_uri);
		    $items = array_slice($rss->items, 0);
		    foreach ($items as $item )
		    {
		        $post_data['title']         = $item['title'];
		        $post_data['slug']          = '';
		        $post_data['date']          = $item['pubdate'];
		        $post_data['private']       = '';
		        $post_data['tags']          = '';
		        $post_data['category']      = $rss_category;
            
            if($Blog->getSettingsData('rssinclude') == 'Y') {
              if(!empty($item['content']['encoded'])) {
                $post_data['content']   = htmlentities($item['content']['encoded'],ENT_QUOTES,'iso-8859-1');
              } else {
                $post_data['content']   = htmlentities($item['summary'],ENT_QUOTES,'iso-8859-1').'<p class="blog_auto_import_readmore"><a href="'.$item['link'].'" target="_blank">'.i18n_r(BLOGFILE.'/READ_FULL_ARTICLE').'</a></p>';
              }
            } else {
              $post_data['content']     = htmlentities($item['summary'],ENT_QUOTES,'iso-8859-1').'<p class="blog_auto_import_readmore"><a href="'.$item['link'].'" target="_blank">'.i18n_r(BLOGFILE.'/READ_FULL_ARTICLE').'</a></p>';
            }
		        $post_data['excerpt']       = $Blog->create_excerpt($item['summary'],0,$Blog->getSettingsData("excerptlength"));
		        $post_data['thumbnail']     = auto_import_thumbnail($item);
		        $post_data['current_slug']  = '';
            $post_data['author']        = htmlentities('<a href="'.$rss_uri.'">RSS Feed</a>',ENT_QUOTES,'iso-8859-1');

		        $Blog->savePost($post_data, true);
            
            echo '<p class="blog_rss_post_added">'.i18n_r(BLOGFILE.'/ADDED').': '.$post_data['title'].'</p>';
		    }
		}
	}
}
