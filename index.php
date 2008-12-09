<?php
/**
 * language switch
 *
 * redirects to the language version according to the browser language
 *
 * @author Andreas Demmer <mail@andreas-demmer>
 */

if (isset ($_POST['language']) && (bool)$_POST['language']) {
	/* language was manually selected */
	header ('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $_POST['language'] . '/');
} else {
	/* detect browser language */
	$browserLanguages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

	foreach ($browserLanguages as $key => $value) {
		$value = strtolower ($value);
		$value = str_replace ('_', '-', $value);

		// search for i18n AND l10n
		if (file_exists ($value)) {
			header ('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $value . '/');
			exit;
		}

		// no i18n AND l10n found, try only i10n
		preg_match ('/^[a-z]+/', $value, $matches);

		if (is_array($matches) && isset($matches['0']) && file_exists ($matches['0'])) {
			header ('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $matches['0'] . '/');
			exit;
		}
	}

	/* fallback language */
	header ('Location: http://' . $_SERVER['HTTP_HOST'] . '/en/');
}
?>