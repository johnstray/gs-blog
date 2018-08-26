<?php if(!defined("IN_GS")){die('You cannot load this file directly!');} // Security Check
/**
 * The SiteMap Cass
 * Handles everything to do with integrating the Blog to the SiteMap.xml file
 *
 * @return void
 */

class GSBlog_SiteMapManager {
    
    public $SiteMap = null;
    private $filePath = GSROOTPATH . 'sitemap.xml';
    
    private $changefreq = "weekly";
    private $priority = "0.2";
    
    private $seourls = false;
    
    /**
     * Class Constructor
     * - Loads up the sitemap from file or creates a blank one if it doesn't exist
     * - Gets and defines some settings required by the class
     *
     * @ return void
     */
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

    /**
     * Class Destructor
     * - Writes the sitemap back to file once we're finished with it.
     *
     * @return bool True: Save successful, False: Error in saving
     */
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

    /**
     * Adds a post to the sitemap
     *
     * @param string $slug - The slug of the post
     * @param string $moddate - The modification date of the post
     * @return void
     */
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

    /**
     * Removes a post from the sitemap
     *
     * @param object $post_data - An object containing the posts data
     * @return void
     */
    public function removePost($post_data) {
        
        foreach ( $this->SiteMap as $SitemapItem ) {
            if ( $SitemapItem['slug'] == $post_data->slug ) {
                $oNode = dom_import_simplexml($SitemapItem);
                $oNode->parentNode->removeChild($oNnode);
            }
        }
        
        $this->removeCategory($post_data->category);
        $this->removeArchive(date('Ym', strtotime($post_data->date)));
        $this->removeTags($post_data->tags);
        
    }

    /**
     * Updates the urls in the sitemap - Used when prettyurl setting is changed
     *
     * @return void
     */
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

    /**
     * Adds a category to the sitemap
     *
     * @param string $item - The category's name or a post's slug
     * @param bool $isSlug - Is $item a post slug to get the category from?
     * @return void
     */
    public function addCategory($item, $isSlug = false) {
        
        $GSBlog = new Blog();
        
        if ( $isSlug ) {
            # Identify the category that slug belongs to
            $post = $GSBlog->getPostData(BLOGPOSTSFOLDER . $item . ".xml");
            $item = (string) $post->category;
        }
        
        # Find if category exists
        $exists = false;
        foreach ( $this->SiteMap as $SitemapItem ) {
            $attributes = $SitemapItem->attributes();
            if ( isset($attributes['type']) && $attributes['type'] == 'category' ) {
                if ( $attributes['category'] == $item ) {
                    $exists = true; break;
                }
            }
        }
        
        if ( !$exists ) {
            # Generate Category URL
            $locURL = $GSBlog->get_blog_url('category') . $item;
            
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
        
    }

    /**
     * Removes a category from the sitemap
     *
     * @param string - The category name to remove
     * @return void
     */
    public function removeCategory($category) {
        
        # First check that there are no posts in the category
        $GSBlog = new Blog();
        $all_posts = $GSBlog->listPosts();
        $in_use = false;
        foreach ( $all_posts as $post ) {
            $post = $GSBlog->getPostData($post);
            if ( $post->category == $category ) {
                $in_use = true;
                break;
            }
        }
        
        # Delete the category if there are no posts in the category
        if ( !$in_use ) {
            foreach ( $this->SiteMap as $SitemapItem ) {
                $attributes = $SitemapItem->attributes();
                if ( isset($attributes['type']) && $attributes['type'] == "category") {
                    if ( $attributes['category'] == $category ) {
                        $oNode = dom_import_simplexml($SitemapItem);
                        $oNode->parentNode->removeChild($oNnode);
                    }
                }
            }
        }
        
    }

    /**
     * Adds an archive item to the sitemap
     *
     * @param string $item - An archive date or A post slug
     * @param bool $isSlug - Is $item an archive or a post slug to get the archive date from?
     * @return void
     */
    public function addArchive($item, $isSlug = false) {
        
        $GSBlog = new Blog;
        
        if ( $isSlug ) {
            # Identify the archive that slug belongs to
            $post = $GSBlog->getPostData(BLOGPOSTSFOLDER . $item . ".xml");
            $postDate = $post->date;
            $item = date("Ym", strtotime($postDate));
        }
        
        # Find if archive exists
        $exists = false;
        foreach ( $this->SiteMap as $SitemapItem ) {
            $attributes = $SitemapItem->attributes();
            if ( isset($attributes['type']) && $attributes['type'] == 'archive' ) {
                if ( $attributes['archive'] == $item ) {
                    $exists = true; break;
                }
            }
        }
        
        if ( !$exists ) {
            # Generate Archive URL
            $locURL = $GSBlog->get_blog_url('archive') . $item;
            
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
        
    }

    /**
     * Removes an archive from the sitemap
     *
     * @param string $archive - The archive date to remove
     * @return void
     */
    public function removeArchive($archive) {
        
        # First check to see if any posts are in this archive
        $GSBlog = new Blog();
        $all_posts = $GSBlog->listPosts();
        $in_use = false;
        foreach ( $all_posts as $post ) {
            $post = $GSBlog->getPostData($post);
            $post_date = (string) $post->date;
            $post_arch = date('Ym', strtotime($post_date));
            if ( $post_arch == $archive ) {
                $in_use = true;
                break;
            }
        }
        
        if ( !$in_use ) {
            foreach ( $this->SiteMap as $SitemapItem ) {
                $attributes = $SitemapItem->attributes();
                if ( isset($attributes['type']) && $attributes['type'] == "archive") {
                    if ( $attributes['archive'] == $archive ) {
                        $oNode = dom_import_simplexml($SitemapItem);
                        $oNode->parentNode->removeChild($oNnode);
                    }
                }
            }
        }
        
    }

    /**
     * Adds tag items to the sitemap
     *
     * @param array/string $item - An array of tags, a single tag as string, or a posts slug
     * @param bool $isSlug - Is $item a post slug to get the tags from?
     * @return void
     */
    public function addTags($item, $isSlug = false) {
        
        # Make sure $tags becomes array if only one tag was passed as a string
        if ( !is_array($item) && !$isSlug ) { $item = array($item); }
        
        $GSBlog = new Blog;
        
        if ( $isSlug ) {
            # Identify the tags that slug belongs to
            $post = $GSBlog->getPostData($item);
            $postTags = $post->tags;
            $item = explode(',', $postTags);
        }
        
        foreach ($item as $tag) {
            
            # Find if tag exists
            $exists = false;
            foreach ( $this->SiteMap as $SitemapItem ) {
                $attributes = $SitemapItem->attributes();
                if ( isset($attributes['type']) && $attributes['type'] == 'tag' ) {
                    if ( $attributes['tag'] == $tag ) {
                        $exists = true; break;
                    }
                }
            }
            
            if ( !$exists ) {
                # Generate Tag URL
                $locURL = $GSBlog->get_blog_url('tag') . $tag;
                
                # Add the tags to the SiteMap
                $newItem = $this->SiteMap->addChild( 'url', '' );
                $newItem->addAttribute( 'type', 'blog-tag' );
                $newItem->addAttribute( 'tag', $tag );
                $newItem->addChild( 'loc', htmlspecialchars($locURL) );
                $tmpDate = date("Y-m-d H:i:s", time());
                $newItem->addChild( 'lastmod', makeIso8601TimeStamp($tmpDate) );
                $newItem->addChild( 'changefreq', $this->changefreq );
                $newItem->addChild( 'priority', $this->priority );
            }
        }
        
    }

    /**
     * Removes a tag from the sitemap
     *
     * @param string $tag - A comma separated string list of tags to remove
     * @return void
     */
    public function removeTags($tag) {
        
        # Generate a list of tags being used
        $GSBlog = new Blog();
        $all_posts = $GSBlog->listPosts();
        $used_tags = array();
        
        foreach ( $all_posts as $post ) {
            $post = $GSBlog->getPostData($post);
            $post_tags = explode(',', $post->tags);
            foreach ( $post_tags as $ptag ) {
                $used_tags[] = $ptag;
            }
        }
        
        
        $tags = explode(',', $tag);
        foreach ( $tags as $rtag ) {
            
            # Delete the tag if its not in the array of used tags
            if ( !in_array($rtag, $used_tags) ) {
                foreach ( $this->SiteMap as $SitemapItem ) {
                    $attributes = $SitemapItem->attributes();
                    if ( isset($attributes['type']) && $attributes['type'] == "tag") {
                        if ( $attributes['tag'] == $rtag ) {
                            $oNode = dom_import_simplexml($SitemapItem);
                            $oNode->parentNode->removeChild($oNnode);
                        }
                    }
                }
            }
        }
        
    }

    /**
     * Formats an XML string as human readable
     *
     * @param string $data - The XML string to format
     * @return string - The formatted XML string
     */
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
