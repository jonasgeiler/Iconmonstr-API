<?php


class Collection {
	public static function crawlCategories () {
		$categories = [];

		$crawler = new Crawler("https://iconmonstr.com/collections/");

		if (!$crawler->finder)
			return false;

		$categoryItems = $crawler->getElementByClass('container-tags-thumb');

		foreach ($categoryItems as $item) {
			$newCategory = [];

			$categoryLink = $item->getElementsByTagName('a')
			                     ->item(0)
			                     ->getAttribute('href');
			preg_match('/iconmonstr\.com\/(.+)\//', $categoryLink, $matches);
			$newCategory['slug'] = $matches[1];

			$newCategory['name'] = trim($item->getElementsByTagName('h3')
			                                 ->item(0)->textContent);

			$categories[] = $newCategory;
		}

		return $categories;
	}

	public static function crawlCollections ($page = 1, $category = 'collections') {
		$collections = [];

		$crawler = new Crawler("https://iconmonstr.com/$category/page/$page");

		if (!$crawler->finder)
			return [false, false];

		$contentItems = $crawler->getElementByClass('content-items-thumb');

		foreach ($contentItems as $item) {
			if (!is_numeric($item->getAttribute('id')))
				continue;

			$newCollection = [];

			$collectionLink = $item->getElementsByTagName('a')
			                       ->item(0)
			                       ->getAttribute('href');
			preg_match('/iconmonstr\.com\/(.+)\//', $collectionLink, $matches);
			$newCollection['slug'] = $matches[1];

			$newCollection['name'] = trim($item->getElementsByTagName('h3')
			                                   ->item(0)->textContent);

			$newCollection['thumbnail'] = $item->getElementsByTagName('img')
			                                   ->item(0)
			                                   ->getAttribute('src');

			$collections[] = $newCollection;
		}

		return [$collections, Pagination::getPageCount($crawler)];
	}

	public static function crawlCollection ($collectionSlug, $page = 1) {
		$collection = [];
		$collection['url'] = "https://iconmonstr.com/$collectionSlug/page/$page";

		$crawler = new Crawler($collection['url']);

		if (!$crawler->finder)
			return [false, false];

		$collection['icons'] = [];
		$iconItems = $crawler->getElementByClass('content-items-thumb');
		foreach ($iconItems as $item) {
			if (!is_numeric($item->getAttribute('id')))
				continue;

			$newIcon = [];

			$iconLink = $item->getElementsByTagName('a')
			                 ->item(0)
			                 ->getAttribute('href');
			preg_match('/iconmonstr\.com\/(.+)-\w+/', $iconLink, $matches);
			$newIcon['slug'] = $matches[1];

			$newIcon['name'] = trim($item->getElementsByTagName('h3')
			                             ->item(0)->textContent);

			$newIcon['previewImage'] = $item->getElementsByTagName('img')
			                                ->item(0)
			                                ->getAttribute('src');

			$collection['icons'][] = $newIcon;
		}

		$containerToggleBtns = $crawler->getElementByClass('container-toggle-btn');
		$collection['category'] = trim($crawler->getElementByClass('level2', $containerToggleBtns)->textContent);
		$collection['name'] = trim($crawler->getElementByClass('active', $containerToggleBtns)->textContent);

		return [$collection, Pagination::getPageCount($crawler)];
	}
}