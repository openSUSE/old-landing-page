<?php
/**
 * language switch
 *
 * redirects to the language version according to the browser language
 *
 * @author Andreas Demmer <mail@andreas-demmer>
 */

/* FIXME: isn't there a way to get the subdir properly so I can test this easily? */
$host = $_SERVER['HTTP_HOST'];

if (isset ($_POST['language']) && (bool)$_POST['language']) {
	/* language was manually selected */
	header ('Location: http://' . $host . '/' . $_POST['language'] . '/');
} else {
	/* detect browser language */
	$browserLanguages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

	foreach ($browserLanguages as $key => $value) {
		$value = strtolower ($value);
		$value = str_replace ('_', '-', $value);

		// search for i18n AND l10n
		if (file_exists ($value)) {
			header ('Location: http://' . $host . '/' . $value . '/');
			exit;
		}

		// no i18n AND l10n found, try only i10n
		preg_match ('/^[a-z]+/', $value, $matches);

		if (is_array($matches) && isset($matches['0']) && file_exists ($matches['0'])) {
			header ('Location: http://' . $host . '/' . $matches['0'] . '/');
			exit;
		}
	}

	/* fallback language */
	header ('Location: http://' . $host . '/en/');
}
?>
