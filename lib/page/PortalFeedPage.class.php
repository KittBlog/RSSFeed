<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractFeedPage.class.php');

class PortalFeedPage extends AbstractFeedPage {
public $rssFeed = "";
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// cache
		WCF::getCache()->addResource('portalRSSFeed-'.$this->limit.'-'.$this->hours, WSIP_DIR.'cache/cache.portalRSSFeed-'.$this->limit.'-'.$this->hours.'.php', WSIP_DIR.'lib/system/cache/CacheBuilderPortalFeed.class.php', 900, 3600);
	}
	
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// Cache lesen
		$this->rssFeed = WCF::getCache()->get('portalRSSFeed-'.$this->limit.'-'.$this->hours);			
	}
    
    
	/**
	 * @see Page::show()
	 */
	public function show() {
		parent::show();
				
		// Feed ausgeben ohne Template
		echo  "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<!DOCTYPE rss PUBLIC \"-//Netscape Communications//DTD RSS 2.0//EN\" \"http://my.netscape.com/publish/formats/rss-2.0.dtd\">
<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
        <channel>
                <title>KittBlog Portal Feed</title>
                <link>".PAGE_URL."</link>
                <description><![CDATA[".StringUtil::encodeHTML("Dein Kit für Stile, Software und Knowhow")."]]></description>
                <language>de</language>
                <image>
                        <title>KittBlog Portal Feed</title>
                        <link>".PAGE_URL."</link>
                        <url>".PAGE_URL."/".RELATIVE_WCF_DIR."/images/kittblogBlack/logo.png</url>
                </image>
        ".$this->rssFeed."
        </channel>	
</rss>";
	}
}
?>