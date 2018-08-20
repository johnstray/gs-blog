<?php if(!defined("IN_GS")){die('You cannot load this file directly!');} // Security Check

class GSBlog_SiteMapManager {
    
    public $SiteMap = null;
    private $filePath = GSROOTPATH . 'sitemap.xml';
    
    private $changefreq = "weekly";
    private $priority = "0.2";
    
    private $seourls = false;
    
    function __construct($changefreq = "weekly", $priority = "1.0") {
        
        if ( file_exists( $this->filePath ) ) {
            # Get the current site map and load it into the variable
            $this->SiteMap = simplexml_load_file ( $this->filePath );
        } else {
            # Site map is non-existant. Create a new SimpleXMLObject with an empty sitemap.
            $this->SiteMap = new SimpleXMLObject('<?xml version="1.0" encoding="utf-8"?><urlset/>');
            $this->SiteMap->addAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd', 'http://www.w3.org/2001/XMLSchema-instance');
            $this->SiteMap->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        }
        
        # Get and define some settings.
        $GSBlog = new Blog;
        if ( $GSBlog->getSettingsData( "prettyurls" ) == "Y" ) {
            $this->seourls = true;
        } else {
            $this->seourls = false;
        }
        
    }
    
    function __destruct() {
        
        # Has the site map been disabled in gsconfig.php?
        if ( getDef('GSNOSITEMAP',true) ) { return; }
        
        # Let plugins filter the xml, then check that they have returned valid xml
        $this->SiteMap = exec_filter('sitemap', $this->SiteMap);
        if ( !is_object($this->SiteMap) ) {
            # SiteMap is no longer valid, a plugin stuffed it up, make a note in the debugLog
            debugLog("[GSBlog: SiteMap.class - __destruct()] Error saving sitemap.xml: A plugin has caused malformation of the XML structure.");
            return false;
        } else {
        
            # Format the XML file as human readable if setting is enabled
            if ( getDef('GSFORMATXML',true) ) { $this->formatXmlString($this->SiteMap); }
            
            # Write the SiteMap data back to the XML file
            if ( XMLSave( $this->SiteMap, $this->filePath ) ) {
                exec_action('sitemap-aftersave'); # Plugin Action Hook
                
                # Ping search engines upon sitemap generation?
                if ( getDef('GSDONOTPING',true) == false ) {
                    if ( 200 === ($status=pingGoogleSitemaps($SITEURL.'sitemap.xml')) ) {
                        # Sitemap successfully created and pinged
                        return true;
                    } else {
                        # Sitemap created but Pinging failed. Return true, but make a note in the debuglog
                        debugLog("[GSBlog: SiteMap.class - __destruct()] Warning: Failed to ping sitemap to search engines. Sitemap was saved though.");
                        return true;
                    }
                } else {
                    # Sitemap successfully created, but did not ping search engines.
                    return true;
                }
            } else {
                debugLog("[GSBlog: SiteMap.class - __destruct()] Error saving sitemap.xml: Could not write XML structure to file.");
                return false;
            }
        }
    }
    
    public function addPost($slug, $moddate = '' ) {
        
        # Set modification time to now if $moddate is empty
        if ( is_empty($moddate) ) { $moddate = time(); }
        
        # Generate post URL
        $GSBlog = new Blog;
        $locURL = $GSBlog->get_blog_url('post') . $slug;
        
        # Add the new item to the SiteMap Object
        $newItem = $this->SiteMap->addChild( 'url', '' );
        $newItem->addAttribute( 'type', 'blog-post' );
        $newItem->addAttribute( 'slug', $slug );
        $newItem->addChild( 'loc', htmlspecialchars($locURL) );
        $tmpDate = date("Y-m-d H:i:s", strtotime($moddate));
        $newItem->addChild( 'lastmod', makeIso8601TimeStamp($tmpDate) );
        $newItem->addChild( 'changefreq', $this->changefreq );
        $newItem->addChild( 'priority', $this->priority );
        
        # Add Archive entry using $moddate as Archive date
        $archiveDate = date("Ym", strtotime($moddate));
        $this->addArchive($archiveDate);
        
        # Add Category and Tag entry
        $this->addCategory($slug, true);
        $this->addTags($slug, true);
        
    }
    
    public function removePost($slug) {
        
        foreach ( $this->SiteMap as $SitemapItem ) {
            if ( $SitemapItem['slug'] == $slug ) {
                $oNode = dom_import_simplexml($urlItem);
                $oNode->parentNode->removeChild($oNnode);
            }
        }
        
        $this->removeCategory();
        $this->removeArchive();
        $this->removeTags();
        
    }
    
    public function updateURLs() {
        
        $GSBlog = new Blog;
        
        foreach ( $this->SiteMap as $SitemapItem ) {
            $slug = (string) $SitemapItem['slug'];
            if ( !empty($slug) ) {
                $locURL = $GSBlog->get_blog_url('post') . $slug;
                $SitemapItem->loc = $locURL;
            }
        }
        
    }

    public function addCategory($item, $isSlug = false) {
        
        if ( $isSlug ) {
            # Identify the category that slug belongs to
            $GSBlog = new Blog;
            $post = $GSBlog->getPostData($item);
            $item = (string) $post->category;
        }
        
        # Generate Category URL
        $locURL = $GSBlog->get_blog_url('category') . $slug;
        
        # Add the category to the SiteMap
        $newItem = $this->SiteMap->addChild( 'url', '' );
        $newItem->addAttribute( 'type', 'blog-category' );
        $newItem->addAttribute( 'category', $item );
        $newItem->addChild( 'loc', htmlspecialchars($locURL) );
        $tmpDate = date("Y-m-d H:i:s", time());
        $newItem->addChild( 'lastmod', makeIso8601TimeStamp($tmpDate) );
        $newItem->addChild( 'changefreq', $this->changefreq );
        $newItem->addChild( 'priority', $this->priority );
        
    }

    public function removeCategory($category = '') {
        
        # if empty $category
            # loop over all type=blog-category items to see which ones we have
            # loop over all posts, building an array of used categories
            # Compare the 2 arrays generated above, removing categories we don't use
        # else
            # find and remove the requested category item
        
    }

    public function addArchive($item, $isSlug = false) {
        
        if ( $isSlug ) {
            # Identify the archive that slug belongs to
            $GSBlog = new Blog;
            $post = $GSBlog->getPostData($item);
            $postDate = $post->date;
            $item = date("Ym", strtotime($postDate));
        }
        
        # Generate Archive URL
        $locURL = $GSBlog->get_blog_url('archive') . $slug;
        
        # Add the archive to the SiteMap
        $newItem = $this->SiteMap->addChild( 'url', '' );
        $newItem->addAttribute( 'type', 'blog-archive' );
        $newItem->addAttribute( 'archive', $item );
        $newItem->addChild( 'loc', htmlspecialchars($locURL) );
        $tmpDate = date("Y-m-d H:i:s", time());
        $newItem->addChild( 'lastmod', makeIso8601TimeStamp($tmpDate) );
        $newItem->addChild( 'changefreq', $this->changefreq );
        $newItem->addChild( 'priority', $this->priority );
        
    }

    public function removeArchive($archive = '') {
        
        # if empty $archive
            # loop over all type=blog-archive items to see which ones we have
            # loop over all posts, building an array of used archives
            # Compare the 2 arrays generated above, removing archives we don't use
        # else
            # find and remove the requested archive item
        
    }

    public function addTags($item, $isSlug = false) {
        
        # Make sure $tags becomes array if only one tag was passed as a string
        if ( !is_array($tags) ) { $tags = array($tags); }
        
        if ( $isSlug ) {
            # Identify the tags that slug belongs to
            $GSBlog = new Blog;
            $post = $GSBlog->getPostData($item);
            $postTags = $post->tags;
            $item = explode(',', $postTags);
        }
        
        # Generate Tag URL
        $locURL = $GSBlog->get_blog_url('tag') . $slug;
        
        # Add the tags to the SiteMap
        $newItem = $this->SiteMap->addChild( 'url', '' );
        $newItem->addAttribute( 'type', 'blog-tag' );
        $newItem->addAttribute( 'tag', $item );
        $newItem->addChild( 'loc', htmlspecialchars($locURL) );
        $tmpDate = date("Y-m-d H:i:s", time());
        $newItem->addChild( 'lastmod', makeIso8601TimeStamp($tmpDate) );
        $newItem->addChild( 'changefreq', $this->changefreq );
        $newItem->addChild( 'priority', $this->priority );
        
    }

    public function removeTags($tag = '') {
        
        # if empty $tag
            # loop over all type=blog-tag items to see which ones we have
            # loop over all posts, building an array of used tags
            # Compare the 2 arrays generated above, removing tags we don't use
        # else
            # find and remove the requested tag item
        
    }
    
    private function formatXmlString( $data ) {
 
        if(gettype($data) === 'object') $data = $data->asXML();

        //Format XML to save indented tree rather than one line
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($data);
 
        $ret = $dom->saveXML();
        return $ret;
    }
    
}
