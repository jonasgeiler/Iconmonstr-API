<?php


class IconRequest {
	public static function crawlIconRequests () {
		$iconRequests = [];

		$crawler = new Crawler("https://iconmonstr.com/icon-request");

		if (!$crawler->finder)
			return false;

		$requestItems = $crawler->getElementByClass('container-leaderboard');

		foreach ($requestItems as $item) {
			$newIconRequest = [];

			$requestLink = $item->getAttribute('href');
			preg_match('/iconmonstr\.com\/request-(.+)\//', $requestLink, $matches);
			$newIconRequest['slug'] = $matches[1];

			$titleContainer = $crawler->getElementByClass('container-leaderboard-title', $item);
			$newIconRequest['name'] = trim($titleContainer->getElementsByTagName('h3')
			                                              ->item(0)->textContent);

			$numberContainer = $crawler->getElementByClass('container-leaderboard-number', $item);
			$newIconRequest['position'] = intval(trim($numberContainer->getElementsByTagName('h3')
			                                                          ->item(0)->textContent));

			$likeContainer = $crawler->getElementByClass('container-leaderboard-like', $item);
			$newIconRequest['likes'] = intval(str_replace(',', '', trim($likeContainer->getElementsByTagName('h3')
			                                                                          ->item(0)->textContent, "+ \t\n\r\0\x0B")));

			$iconRequests[] = $newIconRequest;
		}

		return $iconRequests;
	}

	public static function crawlIconRequest ($slug) {
		$iconRequest = [];
		$iconRequest['url'] = "https://iconmonstr.com/request-$slug";

		$crawler = new Crawler($iconRequest['url']);

		if (!$crawler->finder)
			return false;

		$iconRequest['date'] = [];
		$iconRequest['date']['formatted'] = trim($crawler->getElementByClass('content-top-subtitle')->textContent);
		$iconRequest['date']['timestamp'] = strtotime($iconRequest['date']['formatted']);

		$iconRequest['name'] = trim($crawler->getElementByClass('request-title')->textContent);

		$iconRequest['likes'] = intval(str_replace(',', '', trim($crawler->getElementByClass('count-box')->textContent)));

		return $iconRequest;
	}
}