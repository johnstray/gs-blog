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
    
    public function addItem($slug, $moddate = "time()" ) {
        
        # Generate post URL
        $GSBlog = new Blog;
        $locURL = $GSBlog->get_blog_url('post') . $slug;
        
        # Add the new item to the SiteMap Object
        $newItem = $this->SiteMap->addChild( 'url', '' );
        $newItem->addAttribute( 'slug', $slug );
        $newItem->addChild( 'loc', htmlspecialchars($locURL) );
        $newItem->addChild( 'lastmod', date(DATE_ISO8601, strtotime($moddate)) ); // @TODO: Add timezone support
        $newItem->addChild( 'changefreq', $this->changefreq );
        $newItem->addChild( 'priority', $this->priority );
        
    }
    
    public function removeItem($slug) {
        
        foreach ( $this->SiteMap as $urlItem ) {
            if ( $urlItem['slug'] == $slug ) {
                $oNode = dom_import_simplexml($urlItem);
                $oNode->parentNode->removeChild($oNnode);
            }
        }
        
    }
    
    public function updateURLs() {
        
        $GSBlog = new Blog;
        
        foreach ( $xml as $urlItem ) {
            if ( isset($urlItem['slug']) ) {
                $locURL = $GSBlog->get_blog_url('post') . $urlItem['slug'];
                $urlItem->loc = $locURL;
            }
        }
        
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
