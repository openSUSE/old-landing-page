<?php
/**
 * administrative interface for "What's hot" section on openSUSE.org frontpage
 *
 * @author Andreas Demmer <mail@andreas-demmer.de>
 */

define ('CONTENT_FILE', '../whats_hot.html');

require_once 'my.exception.php';
require_once 'template.class.php';

try {
	preg_match('/\/([a-z]{2})\/admin\//i', $_SERVER['PHP_SELF'], $matches);

	$template = new template ('../../whats_hot/template.html');
	$template->language = $matches[1];

	if (isset ($_POST['save'])) {
		file_put_contents (CONTENT_FILE, $_POST['content']);

		$message = $template->addLoop ('message');
		$message->text = 'File was saved successfully.';
	} else {
		$template->addLoop ('wysiwyg_editor');

		$form = $template->addLoop ('form');
		$form->content = file_get_contents (CONTENT_FILE);
	}

	echo $template->fetch ();

} catch (myException $e) {
	echo $e->getMessage ();
} catch (Exception $e) {
	echo $e->getMessage ();
}
?>