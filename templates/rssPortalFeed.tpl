<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/">

	<channel>
		<title>KittBlog Portal Feed</title>
		<atom:link href="'.PAGE_URL.'/index.php?page=PortalFeed" rel="self" type="application/rss+xml" />
		<link>".PAGE_URL."</link>
		<description><[!CDATA[{lang}wcf.global.index.title.description{/lang}]]></description>
		<language>de-DE</language>
		<image>
			<title>{lang}wsip.rssfeed.title{/lang}</title>
			<link>".PAGE_URL."</link>
			<url>".PAGE_URL."/".RELATIVE_WCF_DIR."/images/kittblogBlack/logo.png</url>
		</image>
		<sy:updatePeriod>hourly</sy:updatePeriod>
		<sy:updateFrequency>1</sy:updateFrequency>

		// ToDo: add rss feeds
		{foreach} // Articles
			<item>
				<title>'.StringUtil::encodeHTML($entry->subject).'</title>
				<author>'.StringUtil::encodeHTML($entry->username).'</author>
				<link>'.StringUtil::encodeHTML(PAGE_URL.'/'.$entry->getURL()).'</link>
				<comments>'.StringUtil::encodeHTML(PAGE_URL.'/'.$entry->getURL()).'#comments</comments>
				<guid>'.StringUtil::encodeHTML(PAGE_URL.'/'.$entry->getURL()).'</guid>
				<pubDate>'.date("r", $entry->time).'</pubDate>
				<dc:creator>'.StringUtil::encodeHTML($entry->username).'</dc:creator>
				<description><![CDATA['.StringUtil::decodeHTML($entry->getFormattedTeaser()).']]></description>
				<content:encoded><![CDATA['.StringUtil::decodeHTML($entry->getFormattedTeaser()).']]></content:encoded>
			</item>
		{/foreach}
		{foreach} // News
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
		{/foreach}
		{foreach} // Reviews
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
		{/foreach}
	</channel>
</rss>