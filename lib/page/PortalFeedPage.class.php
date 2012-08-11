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

		// ToDo: add template
	}
}
?>