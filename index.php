<?php

require_once('lib/limonade.php');

function before () {
	ini_set("allow_url_fopen", 1);
}

function displayError($errno, $errstr, $errfile = null, $errline = null) {
	return json([
		'success' => false,
		'error'   => [
			'code'    => $errno,
			'message' => $errstr,
			'file'    => str_replace('\\', '/', $errfile),
			'line'    => $errline,
		],
	], JSON_PRETTY_PRINT);
}

function not_found (...$args) {
	return displayError(...$args);
}

function server_error (...$args) {
	return displayError(...$args);
}

//------

dispatch_get('/icons/search/', 'getSearchIcons'); // takes ?page= and ?filter=(fill/bold/thin) and ?query=
dispatch_get('/icons/popular/', 'getPopularIcons'); // takes ?page=
dispatch_get('/icons/new/', 'getNewIcons'); // takes ?page= and ?filter=(fill-bold/thin)
dispatch_get('/icons/:slug/', 'getIcon'); // takes ?fileType=

dispatch_get('/categories/', 'getCategories');
dispatch_get('/categories/:slug', 'getCategory'); // takes ?page=
dispatch_get('/collections/', 'getCollections'); // takes ?page=
dispatch_get('/collections/:slug', 'getCollection'); // takes ?page=

dispatch_get('/icon-requests/', 'getIconRequests');
dispatch_get('/icon-requests/:slug', 'getIconRequest');

//------

run();