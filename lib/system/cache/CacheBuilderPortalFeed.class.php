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
 * @author Matthias Kittsteiner & Dennis Kraffczyk
 */   
class CacheBuilderPortalFeed implements CacheBuilder {
	public $additionalSqlSelects = "";
	public $currentRow = array();
	public $data = "";
	public $limit = 0, $hours = 0;
	
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
				 'newsEntry' AS 'type'
			FROM wsip".WSIP_N."_news_entry n
				".($this->hours ? "WHERE n.time > ".(TIME_NOW - $this->hours * 3600) : '').")
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
				".($this->hours ? "WHERE n.time > ".(TIME_NOW - $this->hours * 3600) : '').")
				".($this->additionalSqlSelects != '' ? $this->additionalSqlSelects : '')."
		ORDER BY time DESC";

		// get items
		$result = WCF::getDB()->sendQuery($sql, $this->limit);
		// parse items
		while ($row = WCF::getDB()->fetchArray($result)) {
		  switch ($row['type']) {
				case 'article':
					$entry = new Article(null, $row);
					$this->data .= '
						<item>
							<title>'.StringUtil::encodeHTML($entry->subject).'</title>
							<author>'.StringUtil::encodeHTML($entry->username).'</author>
							<link>'.StringUtil::encodeHTML(PAGE_URL.'/'.$entry->getURL()).'</link>
							<guid>'.StringUtil::encodeHTML(PAGE_URL.'/'.$entry->getURL()).'</guid>
							<pubDate>'.date("r", $entry->time).'</pubDate>
							<description><![CDATA['.StringUtil::decodeHTML($entry->getFormattedTeaser()).']]></description>
							<comments>'.StringUtil::encodeHTML(PAGE_URL.'/'.$entry->getURL()).'#comments</comments>
							<dc:creator>'.StringUtil::encodeHTML($entry->username).'</dc:creator>
							<content:encoded><![CDATA['.StringUtil::decodeHTML($entry->getFormattedTeaser()).']]></content:encoded>
						</item>
					';
				break;

				case 'newsEntry':
					$row['entryID'] = $row['id'];
					$entry = new NewsEntry(null, $row);
					$this->data .= '
						<item>
							<title>'.StringUtil::encodeHTML($entry->subject).'</title>
							<author>'.StringUtil::encodeHTML($entry->username).'</author>
							<link>'.StringUtil::encodeHTML(PAGE_URL.'/'.$entry->getURL()).'</link>
							<guid>'.StringUtil::encodeHTML(PAGE_URL.'/'.$entry->getURL()).'</guid>
							<pubDate>'.date("r", $entry->time).'</pubDate>
							<description><![CDATA['.StringUtil::decodeHTML($entry->getFormattedTeaser()).']]></description>
							<comments>'.StringUtil::encodeHTML(PAGE_URL.'/'.$entry->getURL()).'#comments</comments>
							<dc:creator>'.StringUtil::encodeHTML($entry->username).'</dc:creator>
							<content:encoded><![CDATA['.StringUtil::decodeHTML($entry->getFormattedTeaser()).']]></content:encoded>
						</item>
					';
				break;

				case 'review':
					$row['reviewID'] = $row['id'];
				  $authorString = "";
					$entry = new Review(null, $row);
						foreach($entry->writer as $author) {
							if (!empty($authorString)) $authorString .= ", ";
							$authorString .= $author['username'];
						}
					$this->data .= '
						<item>
							<title>'.StringUtil::encodeHTML($entry->subject).'</title>
							<author>'.StringUtil::encodeHTML($authorString).'</author>
							<link>'.StringUtil::encodeHTML(PAGE_URL.'/'.$entry->getURL()).'</link>
							<guid>'.StringUtil::encodeHTML(PAGE_URL.'/'.$entry->getURL()).'</guid>
							<pubDate>'.date("r", $entry->time).'</pubDate>
							<description><![CDATA['.StringUtil::decodeHTML($entry->getFormattedTeaser()).']]></description>
							<comments>'.StringUtil::encodeHTML(PAGE_URL.'/'.$entry->getURL()).'#comments</comments>
							<dc:creator>'.StringUtil::encodeHTML($authorString).'</dc:creator>
							<content:encoded><![CDATA['.StringUtil::decodeHTML($entry->getFormattedTeaser()).']]></content:encoded>
						</item>
						';
				break;

				default:
					// hook to parsing additional items
					$this->currentRow = $row;
					EventHandler::fireAction($this, 'parseAdditionalItems');
			}
		}
	return $this->data;
	}
}
?>