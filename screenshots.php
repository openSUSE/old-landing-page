<?php
/**
 * load the screenshots from the JSON service and render them as unordered list
 *
 * @author Andreas Demmer <mail@andreas-demmer.de>
 */

$jsonUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/screenshot_gallery/cached_screenshots.json';
$jsonString = file_get_contents($jsonUrl);
$jsonClass = json_decode($jsonString);
$screenshots = get_object_vars($jsonClass);
$html = NULL;

foreach ($screenshots as $screenshot) {
	$html .= '<li>';
	$html .= '<h2>' . $screenshot->description . '</h2>';
	$html .= '<a href="' . $screenshot->file_large . '" target="_blank">';
	$html .= '<img src="en/' . $screenshot->file_small . '" />';
	$html .= '</a>';
	$html .= "</li>\n";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>openSUSE.org Screenshots</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="language" content="en" />
    <link rel="alternate" type="application/rss+xml" title="openSUSE news &amp; events" href="http://news.opensuse.org/feed/" />
    <link href="css/screenshots.css" rel="stylesheet" type="text/css" media="screen" />
  </head>
  <body>
    <h1>openSUSE Screenshots</h1>
	<p>
	  <a href="http://www.opensuse.org/">&laquo; www.openSUSE.org</a>
	</p>
	<ul>
	<?php echo $html; ?>
	</ul>
	<p>
	  <a href="http://www.opensuse.org/">&laquo; www.openSUSE.org</a>
	</p>
  </body>
</html>