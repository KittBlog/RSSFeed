<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/****************************************************************************************************
  * @desc       Add customer area link into userpanel.												*
  *																									*
  * @author     Matthias Kittsteiner																* 
  * @copyright  Dennis Kraffczyk 2010																*
  * @website	http://kittblog.de																	*
  * @package	com.woltlab.wcf																		*
  * @subpackage de.kittblog.wcf.customerAreaLink													*
  *************************************************************************************************/

class PortalFeedHeadIncludeListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */

	public function execute($eventObj, $className, $eventName) {
	   
        WCF::getTPL()->append('specialStyles', '<link rel="alternate" type="application/rss+xml" href="'.PAGE_URL.'/index.php?page=PortalFeed" title="'.PAGE_TITLE.' RSS-Feed (Artikel, Nachrichten &amp; Rezensionen)" />');
	}
}
?>