<?php


class Icon {

	private static function getFileTypes (Crawler $crawler) {
		$fileTypes = [];

		$fileTypeSwitches = $crawler->getElementByClass('container-toggle-btn')->childNodes;

		foreach ($fileTypeSwitches as $switch) {
			if (get_class($switch) === 'DOMElement')
				$fileTypes[] = strtolower(trim($switch->textContent));
		}

		return $fileTypes;
	}

	private static function getDownloadLink (Crawler $crawler) {
		// This way of getting the download link is adapted to the actual JavaScript code on the iconmonstr site!

		$downloadKey = substr($crawler->getElementByClass('active-id')
		                              ->getAttribute('id'), 0, 32);

		$date = $crawler->getElementByClass('date')
		                ->getAttribute('id');

		$containerToggleBtn = $crawler->getElementByClass('container-toggle-btn');
		$dirName = '/' . $crawler->getElementByClass('active', $containerToggleBtn)
		                         ->getAttribute('id') . '/iconmonstr-';

		$fileName = $crawler->getElementByClass('download-btn')
		                    ->getAttribute('id');

		$fileExt = '.' . $crawler->getElementByClass('container-content-preview')
		                         ->getAttribute('id');

		return "https://iconmonstr.com/?s2member_file_download_key=$downloadKey&s2member_file_download=$date$dirName$fileName$fileExt";
	}

	private static function getSimilarIcons (Crawler $crawler) {
		$similarIcons = [];

		$similarDivs = $crawler->getElementByClass('content-items-thumb-wrap');

		foreach ($similarDivs as $similarDiv) {
			preg_match(
				'/iconmonstr\.com\/(.+)-\w+/',
				$similarDiv->getElementsByTagName('a')
				           ->item(0)
				           ->getAttribute('href'),
				$matches
			);

			$similarIcons[] = $matches[1];
		}

		return $similarIcons;
	}

	private static function getTags (Crawler $crawler) {
		$tags = [];

		$tagDivs = $crawler->getElementByClass('container-tags-thumb-wrap');

		foreach ($tagDivs as $tagDiv) {
			$tags[] = trim($tagDiv->textContent, "# \t\n\r\0\x0B");
		}

		return $tags;
	}

	private static function getCollection (Crawler $crawler) {
		$collectionLink = $crawler->getElementByClass('container-similar-all')
		                          ->getElementsByTagName('a')
		                          ->item(0)
		                          ->getAttribute('href');


		preg_match('/iconmonstr\.com\/(.+)\//', $collectionLink, $matches);

		return $matches[1];
	}

	private static function getFontCharInfo (Crawler $crawler) {
		$info = [];

		$infoItems = $crawler->getElementByClass('iconic-font-info')->childNodes;

		foreach ($infoItems as $infoItem) {
			if (get_class($infoItem) === 'DOMElement') {
				$infoItemParts = explode(':', $infoItem->textContent);

				$info[strtolower(trim($infoItemParts[0]))] = trim($infoItemParts[1]);
			}
		}

		return $info;
	}


	public static function crawlIconData ($name, $fileType = 'svg') {
		$data = [];
		$data['slug'] = $name;
		$data['url'] = "https://iconmonstr.com/$name-$fileType";

		$crawler = new Crawler($data['url']);

		if (!$crawler->finder)
			return false;

		$data['availableFileTypes'] = self::getFileTypes($crawler);

		if (!in_array($fileType, $data['availableFileTypes']))
			return false;

		if ($fileType === 'font') {
			$data['name'] = trim(explode('-', $crawler->doc->getElementsByTagName('title')
			                                               ->item(0)->textContent)[0]);
			$data['class'] = $crawler->getElementByClass('content-top-title')->textContent;
			$data['code'] = trim($crawler->getElementByClass('iconic-font-code')->textContent);

			$fontCharInfo = self::getFontCharInfo($crawler);
			$data = array_merge($data, $fontCharInfo);
		} else {
			$data['name'] = $crawler->getElementByClass('content-top-title')->textContent;

			$data['previewImage'] = $crawler->getElementByClass('container-content-preview-thumb')
			                                ->getElementsByTagName('img')
			                                ->item(0)
			                                ->getAttribute('src');

			$data['downloadLink'] = self::getDownloadLink($crawler);

			if ($fileType === 'svg' && $data['downloadLink']) {
				$data['embedCode'] = file_get_contents($data['downloadLink']);
			}

			$data['similar'] = self::getSimilarIcons($crawler);
			$data['tags'] = self::getTags($crawler);
			$data['collection'] = self::getCollection($crawler);
		}

		return $data;
	}


	private static function getIconsFromGrid($crawler, $likesOrTime = 'likes', $class = 'content-items-thumb') {
		$icons = [];

		$gridIcons = $crawler->getElementByClass($class);

		foreach ($gridIcons as $icon) {
			if (!is_numeric($icon->getAttribute('id')))
				continue;

			$newIcon = [];

			$iconLink = $icon->getElementsByTagName('a')
			                 ->item(0)
			                 ->getAttribute('href');
			preg_match('/iconmonstr\.com\/(.+)\//', $iconLink, $matches);
			$newIcon['slug'] = $matches[1];

			$iconImage = $icon->getElementsByTagName('img')
			                  ->item(0);
			$newIcon['previewImage'] = $iconImage->getAttribute('src');
			$newIcon['name'] = $iconImage->getAttribute('alt');

			if ($likesOrTime === 'likes') {
				$newIcon['likes'] = intval(str_replace(',', '', trim($icon->getElementsByTagName('h3')
				                                                          ->item(0)->textContent)));
			} else {
				$newIcon['dateInfo'] = [];

				$newIcon['dateInfo']['raw'] = trim($icon->getElementsByTagName('h3')
				                                           ->item(0)->textContent);

				preg_match('/(\d+)\s*(second|minute|hour|day|week|month|year)(s?)\s*ago/', $newIcon['dateInfo']['raw'], $matches);

				$newIcon['dateInfo']['count'] = $matches[1];
				$newIcon['dateInfo']['unit'] = $matches[2];
				$newIcon['dateInfo']['plural'] = ($matches[3] === 's');
			}

			$icons[] = $newIcon;
		}

		return $icons;
	}

	public static function crawlPopularIcons ($page = 1) {
		$crawler = new Crawler("https://iconmonstr.com/popular/page/$page");

		if (!$crawler->finder)
			return [false, false];

		$icons = self::getIconsFromGrid($crawler);

		return [$icons, Pagination::getPageCount($crawler)];
	}


	public static function crawlSearchIcons ($searchQuery, $filter = 'all', $page = 1) {
		$crawler = new Crawler("https://iconmonstr.com/page/$page/?s=$searchQuery&$filter=true");

		if (!$crawler->finder)
			return [false, false];

		$icons = self::getIconsFromGrid($crawler);

		return [$icons, Pagination::getPageCount($crawler)];
	}


	public static function crawlNewIcons ($filter = '', $page = 1) {
		$crawler = new Crawler("https://iconmonstr.com/$filter/page/$page/");

		if (!$crawler->finder)
			return [false, false];

		$icons = self::getIconsFromGrid($crawler, 'time');

		return [$icons, Pagination::getPageCount($crawler)];
	}
}