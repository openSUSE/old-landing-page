<?php
/**
 * refresh "news" section on openSUSE.org frontpage by fetching newsfeed
 *
 * @author Andreas Demmer <mail@andreas-demmer.de>
 */

define ('CONTENT_FILE', $_SERVER['DOCUMENT_ROOT'] . '/news.html');
define ('AMOUNT_NEWS', 5);

require_once 'magpierss-0.72/rss_fetch.inc';

$rss = fetch_rss ('http://news.opensuse.org/feed/');
date_default_timezone_set( 'UTC' );

$html = "<ul>\n";

for ($i = 0; $i < AMOUNT_NEWS; $i++) {
        $item = $rss->items[$i];
	$dateString = date ('M d', $item['date_timestamp']);

        echo "Adding entry: " . $dateString . " " . $item['title'] . "\n";

	$html .= '<li>';
	$html .= '<strong>' . $dateString . ':</strong> ';
	$html .= '<a href="'. $item['link'] . '">' . $item['title'] . "</a>\n";
	$html .= '<span class="clear">&nbsp;</span>';
	$html .= '</li>';
}

$html .= '</ul>';

file_put_contents(CONTENT_FILE, $html);
?>
