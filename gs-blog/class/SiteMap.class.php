<?php if(!defined("IN_GS")){die('You cannot load this file directly!');} // Security Check

class GSBlog_SiteMapManager {
    
    public $SiteMap = null;
    private $filePath = GSROOTPATH . '/sitemap.xml';
    
    private $changefreq = "weekly";
    private $priority = "1.0";
    
    private $seourls = false;
    
    function __construct($changefreq = "weekly", $priority = "1.0") {
        
        if ( file_exists( $this->filePath ) ) {
            # Get the current site map and load it into the variable
            $this->SiteMap = simplexml_load_file ( $this->filePath );
        } else {
            # Site map is non-existant. Create a new SimpleXMLObject with an empty sitemap.
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
    
        # Write the SiteMap data back to the XML file
        if ( XMLSave( $this->SiteMap, $this->filePath ) ) {
            return true;
        } else {
            return false;
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
