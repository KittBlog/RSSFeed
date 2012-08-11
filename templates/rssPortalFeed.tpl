<?xml version="1.0" encoding="{@CHARSET}"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
	<channel>
		<title>{lang}wsip.rssfeed.title{/lang}</title>
		<link>{PAGE_URL}</link>
		<description><[!CDATA[{lang}wcf.global.index.title.description{/lang}]]></description>
		<language>de-DE</language>
		<image>
			<title>{lang}wsip.rssfeed.title{/lang}</title>
			<link>{PAGE_URL}</link>
			<url>{PAGE_URL}/{RELATIVE_WCF_DIR}images/kittblogBlack/logo.png</url>
		</image>
		<pubDate>{@'r'|gmdate:TIME_NOW}</pubDate>
		<lastBuildDate>{@'r'|gmdate:TIME_NOW}</lastBuildDate>
		<generator>WoltLab Community Framework {WCF_VERSION}</generator>
		<ttl>60</ttl>
		
		{foreach from=$data item=$entry}
			<item>
				<title>{$entry->subject}</title>
				<link>{PAGE_URL}/{$entry->getURL()}</link>
				<comments>{PAGE_URL}/{$entry->getURL()}#comments</comments>
				<guid>{PAGE_URL}/{$entry->getURL()}</guid>
				<pubDate>{@'r'|gmdate:$entry->time}</pubDate>
				<description><![CDATA[{$entry->getFormattedTeaser()}]]></description>
				<content:encoded><![CDATA[{$entry->getFormattedTeaser()}]]></content:encoded>
			{if $entry->type == 'article' || $entry->type == 'news'}
				<author>{$entry->username}</author>
				<dc:creator>{$entry->username}</dc:creator>
			{else}
				<author>{implode from=$entry->writer item=authorData}{$authorData[username]}{/implode}</author>
				<dc:creator>{implode from=$entry->writer item=authorData}{$authorData[username]}{/implode}</dc:creator>
			{/if} 				
			</item>
		{/foreach}
	</channel>
</rss>