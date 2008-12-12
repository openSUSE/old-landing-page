<?php
/**
 * screenshot gallery backend
 *
 * reads all images from their directory, parses their description and returns a JSON encoded array
 *
 * @author Andreas Demmer <mail@andreas-demmer.de>
 */

define ('LOCAL_PATH_SCREENSHOTS', '../images/screenshots/thumbs/');
define ('PATH_SCREENSHOTS_LARGE', 'http://static.opensuse.org/hosts/www.o.o/images/screenshots/zoom/');
define ('PATH_SCREENSHOTS_SMALL', 'http://static.opensuse.org/hosts/www.o.o/images/screenshots/thumbs/');

$dir = dir (LOCAL_PATH_SCREENSHOTS);
$screenshots = array ();

while ($file = $dir->read()) {
	if (!preg_match('/^\./', $file)) {
		$filenameChunks = explode ('.', $file);
		$id = $filenameChunks[0];
		$suffix = $filenameChunks[2];
		$description = str_replace ('_', ' ', $filenameChunks[1]);

		$details = array (
			'description' => $description,
			'file_large' => PATH_SCREENSHOTS_LARGE . $id . '.' . $suffix,
			'file_small' => PATH_SCREENSHOTS_SMALL . $file
		);

		$screenshots[$id] = $details;
	}
}

ksort ($screenshots);
echo json_encode($screenshots);
?>