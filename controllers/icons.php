<?php

require_once('models/Icon.php');

function getSearchIcons () {
	$page = 1;

	if (isset($_GET['page']) && is_numeric($_GET['page']))
		$page = $_GET['page'];


	$filter = 'all';
	$allowedFilters = ['all', 'bold', 'fill', 'thin'];

	if (isset($_GET['filter']) && in_array($_GET['filter'], $allowedFilters))
		$filter = $_GET['filter'];


	$searchQuery = '';

	if (isset($_GET['query']))
		$searchQuery = $_GET['query'];
	else
		return json(['success' => false, 'error' => "Parameter 'query' is required!"]);


	[$icons, $pageCount] = Icon::crawlSearchIcons($searchQuery, $filter, $page);

	if (!$icons)
		return json(['success' => false, 'error' => 'Couldn\'t retrieve icons with search query "' . $searchQuery . '", page ' . $page]);

	return json(['success' => true, 'pages' => $pageCount, 'icons' => $icons]);
}



function getPopularIcons () {
	$page = 1;

	if (isset($_GET['page']) && is_numeric($_GET['page']))
		$page = $_GET['page'];


	[$icons, $pageCount] = Icon::crawlPopularIcons($page);

	if (!$icons)
		return json(['success' => false, 'error' => 'Couldn\'t retrieve popular icons, page ' . $page]);

	return json(['success' => true, 'pages' => $pageCount, 'icons' => $icons]);
}



function getNewIcons () {
	$page = 1;

	if (isset($_GET['page']) && is_numeric($_GET['page']))
		$page = $_GET['page'];


	$filter = '';
	$allowedFilters = ['fill-bold', 'thin'];

	if (isset($_GET['filter']) && in_array($_GET['filter'], $allowedFilters))
		$filter = $_GET['filter'];


	[$icons, $pageCount] = Icon::crawlNewIcons($filter, $page);

	if (!$icons)
		return json(['success' => false, 'error' => 'Couldn\'t retrieve new icons, page ' . $page]);

	return json(['success' => true, 'pages' => $pageCount, 'icons' => $icons]);
}



function getIcon ($slug) {
	$fileType = 'svg';
	$fileTypes = ['svg', 'eps', 'psd', 'font']; // TODO: png

	if (isset($_GET['fileType'])) {
		if (!in_array($_GET['fileType'], $fileTypes))
			return json(['success' => false, 'error' => 'Unsupported or unknown file type']);

		$fileType = $_GET['fileType'];
	}


	$iconData = Icon::crawlIconData($slug, $fileType);

	if (!$iconData)
		return json(['success' => false, 'error' => 'Couldn\'t retrieve icon data from icon ' . $slug]);

	return json(['success' => true, 'icon' => $iconData]);
}