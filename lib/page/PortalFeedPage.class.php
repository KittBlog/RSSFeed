<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractFeedPage.class.php');
// wsip imports
require_once(WSIP_DIR.'lib/data/article/Article.class.php');
require_once(WSIP_DIR.'lib/data/news/NewsEntry.class.php');
require_once(WSIP_DIR.'lib/data/review/Review.class.php');

/**
 * Shows the rss-portal-feed page.
 *
 * @author 	Matthias Kittsteiner
 * @copyright 	2010-2012 KittBlog
 * @license 	LGPL <http://www.gnu.org/licenses/lgpl.html>
 * @package 	de.kittblog.wsip.rssFeed
 */
class PortalFeedPage extends AbstractFeedPage {
	/**
	 * feed data
	 * @var array<mixed>
	 */
	public $feedData = array();

	/**
	 * @see AbstractPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		WCF::getCache()->addResource('portalRSSFeed-'.$this->limit.'-'.$this->hours, WSIP_DIR.'cache/cache.portalRSSFeed-'.$this->limit.'-'.$this->hours.'.php', WSIP_DIR.'lib/system/cache/CacheBuilderPortalFeed.class.php', 900, 3600);
		$this->feedData = WCF::getCache()->get('portalRSSFeed-'.$this->limit.'-'.$this->hours);
	}
	
	/**
	 * @see AbstractPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign('data', $this->feedData);
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		parent::show();
	
		// send content
		WCF::getTPL()->display('rssPortalFeed', false);
	}
}
?>