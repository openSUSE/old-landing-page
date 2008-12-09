<?php
/**
 * simple search redirector: posts searchstring to the selected site module
 *
 * @author Andreas Demmer <mail@anddreas-demmer.de>
 */

switch ($_POST['searchscope']) {
	case 'news':
		$url = 'http://news.opensuse.org/?s=' . $_POST['searchstring'];
		break;

	case 'software':
		$url = 'http://software.opensuse.org/search?q=' . $_POST['searchstring'];
		break;

	case 'wiki':
		$url = 'http://' . $_POST['language'] . '.opensuse.org/Special:Search?search=' . $_POST['searchstring'];
		break;

	case 'blogs':
		$url = 'http://lizards.opensuse.org/?s=' . $_POST['searchstring'];
		break;

	default:
		$url = 'http://' . $_POST['language'] . '.opensuse.org/Special:Search?search=' . $_POST['searchstring'];
}

header ('Location: ' . $url);
?>