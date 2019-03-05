<?php

require_once('models/Collection.php');

function getCategories () {
	$categories = Collection::crawlCategories();

	if (!$categories)
		return json(['success' => false, 'error' => 'Couldn\'t retrieve categories']);

	return json(['success' => true, 'categories' => $categories]);
}

function getCategory ($categorySlug) {
	$page = 1;

	if (isset($_GET['page']) && is_numeric($_GET['page']))
		$page = $_GET['page'];

	[$collections, $pageCount] = Collection::crawlCollections($page, $categorySlug);

	if (!$collections)
		return json(['success' => false, 'error' => 'Couldn\'t retrieve collections from category ' . $categorySlug . ', page ' . $page]);

	return json(['success' => true, 'pages' => $pageCount, 'collections' => $collections]);
}

function getCollections () {
	$page = 1;

	if (isset($_GET['page']) && is_numeric($_GET['page']))
		$page = $_GET['page'];

	[$collections, $pageCount] = Collection::crawlCollections($page);

	if (!$collections)
		return json(['success' => false, 'error' => 'Couldn\'t retrieve collections from all collections, page ' . $page]);

	return json(['success' => true, 'pages' => $pageCount, 'collections' => $collections]);
}

function getCollection ($collectionSlug) {
	$page = 1;

	if (isset($_GET['page']) && is_numeric($_GET['page']))
		$page = $_GET['page'];

	[$collection, $pageCount] = Collection::crawlCollection($collectionSlug, $page);

	if (!$collection)
		return json(['success' => false, 'error' => 'Couldn\'t retrieve collection ' . $collectionSlug . ', page ' . $page]);

	return json(['success' => true, 'pages' => $pageCount, 'collection' => $collection]);
}