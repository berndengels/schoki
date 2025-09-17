<?php
if (! function_exists('trimUrlToDomain')) {
	/**
	 * Extract only the domain from a given URL.
	 *
	 * @param string $url
	 * @return string|null
	 */
	function trimUrlToDomain(string $url): ?string
	{
		$url = trim($url);

		// Ensure it has a scheme, otherwise parse_url may fail
		if (! preg_match('~^https?://~i', $url)) {
			$url = "http://{$url}";
		}

		$host = parse_url($url, PHP_URL_HOST);

		// Remove "www." if you want cleaner output
		return $host ? preg_replace('/^www\./', '', $host) : null;
	}
}
