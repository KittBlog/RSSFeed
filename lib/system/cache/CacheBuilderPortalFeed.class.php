<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');
require_once(WCF_DIR.'lib/system/event/EventHandler.class.php');
// wsip imports
require_once(WSIP_DIR.'lib/data/article/Article.class.php');
require_once(WSIP_DIR.'lib/data/news/NewsEntry.class.php');
require_once(WSIP_DIR.'lib/data/review/Review.class.php');
 
/**
 * Caches the feed entries for the portal feed.
 *
 * @author 	Matthias Kittsteiner
 * @copyright 	2010-2012 KittBlog
 * @license 	LGPL <http://www.gnu.org/licenses/lgpl.html>
 * @package 	de.kittblog.wsip.rssFeed
 */
class CacheBuilderPortalFeed implements CacheBuilder {
	/**
	 * additional sql selects
	 * @var string
	 */
	public $additionalSqlSelects = "";
	
	/**
	 * represents the current row for editing via eventlistener
	 * @var array<mixed>
	 */
	public $currentRow = array();
	
	/**
	 * limit parameters
	 * @var	integer
	 */
	public $limit = 0, $hours = 0;
	
	/**
	 * feed-entries
	 * @var array<mixed>
	 */
	public $data = array();
	
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {			
		list(, $this->limit, $this->hours) = explode('-', $cacheResource['cache']);
		
		// fire event
		EventHandler::fireAction($this, 'shouldSelect');
		
		// build sql query
		$sql = "
			 (SELECT n.firstSectionID,
				n.articleID AS 'id',
				 n.username,
				 n.subject,
				 n.teaser,
				 n.time,
				 'article' AS 'type'
			FROM wsip".WSIP_N."_article n
				".($this->hours ? "WHERE n.time > ".(TIME_NOW - $this->hours * 3600) : '').")
		UNION
		(
			SELECT n.entryID,
				n.entryID AS 'id',
				 n.username,
				 n.subject,
				 n.teaser,
				 n.time,
				 'news' AS 'type'
			FROM wsip".WSIP_N."_news_entry n
				n.isDisabled = 0
				".($this->hours ? "AND WHERE n.time > ".(TIME_NOW - $this->hours * 3600) : '').")
		UNION
		(
			SELECT n.firstSectionID,
				n.reviewID AS 'id',
				 n.username,
				 n.subject,
				 n.teaser,
				 n.time,
				 'review' AS 'type'
			FROM wsip".WSIP_N."_review n
				n.isDisabled = 0
				".($this->hours ? "AND WHERE n.time > ".(TIME_NOW - $this->hours * 3600) : '').")
				".($this->additionalSqlSelects != '' ? $this->additionalSqlSelects : '')."
		ORDER BY time DESC";

		// get items
		$result = WCF::getDB()->sendQuery($sql, $this->limit);
		// parse items
		while ($row = WCF::getDB()->fetchArray($result)) {
			$entry = null;
			
			switch ($row['type']) {
				case 'article':
					$entry = new Article(null, $row);
				break;

				case 'newsEntry':
					$row['entryID'] = $row['id'];
					$entry = new NewsEntry(null, $row);
				break;

				case 'review':
					$row['reviewID'] = $row['id'];
					$entry = new Review(null, $row);
				break;

				default:
					// hook to parsing additional items
					$this->currentRow = $row;
					EventHandler::fireAction($this, 'parseAdditionalItems');
			}
			if ($entry !== null) {
				$this->data[] = $entry;
			}
		}
		return $this->data;
	}
}
?>