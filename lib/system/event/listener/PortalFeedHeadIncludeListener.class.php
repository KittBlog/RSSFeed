<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Includes the link to the feed.
 *
 * @author 	Matthias Kittsteiner
 * @copyright 	2010-2012 KittBlog
 * @license 	LGPL <http://www.gnu.org/licenses/lgpl.html>
 * @package 	de.kittblog.wsip.rssFeed
 */
class PortalFeedHeadIncludeListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		WCF::getTPL()->append('specialStyles', '<link rel="alternate" type="application/rss+xml" href="'.PAGE_URL.'/index.php?page=PortalFeed&amp;format=rss2" title="'.WCF::getLanguage()->get('wsip.rssfeed.title').' '.WCF::getLanguage()->get('wsip.rssfeed.description').'" />');
	}
}
?>