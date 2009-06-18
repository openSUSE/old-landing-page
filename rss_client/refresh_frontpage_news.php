<?php
/**
 * refresh "news" section on openSUSE.org frontpage by fetching newsfeed
 *
 * @author Andreas Demmer <mail@andreas-demmer.de>
 */

define ('CONTENT_FILE', $_SERVER['DOCUMENT_ROOT'] . '/news.html');
define ('AMOUNT_NEWS', 5);

require_once 'rssparser.class.php';

$rss = new rdfParser ('http://news.opensuse.org/feed/');
$items = $rss->fetchItems ();

$html = "<ul>\n";

for ($i = 1; $i <= AMOUNT_NEWS; $i++) {
	$item = array_shift ($items);
	$title = utf8_decode (strip_tags ($item['title']));
	$timestamp = strtotime($item['pubDate']);
	$dateString = date ('M d', $timestamp);

	$html .= '<li>';
	$html .= '<strong>' . $dateString . ':</strong> ';
	$html .= '<a href="'. $item['link'] . '">' . $title . "</a>\n";
	$html .= '<span class="clear">&nbsp;</span>';
	$html .= '</li>';
}

$html .= '</ul>';

file_put_contents(CONTENT_FILE, $html);
?>