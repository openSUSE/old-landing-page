<?php
/**
 * refresh listed screenshots on openSUSE.org frontpage by caching json service result
 *
 * @author Andreas Demmer <mail@andreas-demmer.de>
 */

define ('CONTENT_FILE', $_SERVER['DOCUMENT_ROOT'] . '/screenshot_gallery/cached_screenshots.json');

$jsonUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/screenshot_gallery/service.php';
$jsonString = file_get_contents($jsonUrl);

if ((bool)$jsonString) {
	if (!file_put_contents (CONTENT_FILE, $jsonString)) {
            echo "Couldn't write file!";
        } else {
            echo $jsonString;
        }
} else {
	echo 'FAILURE: empty reply from JSON service';
}
?>