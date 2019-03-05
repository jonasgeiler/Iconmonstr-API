<?php


class Pagination {
	public static function getPageCount(Crawler $crawler) {
		$paginationDiv = $crawler->getElementByClass('container-content-pagination');

		$pageNumbers = $crawler->getElementByClass('page-numbers', $paginationDiv);

		if (!empty($pageNumbers)) {
			return intval(trim($pageNumbers[count($pageNumbers) - 1]->textContent));
		} else {
			return 1;
		}
	}
}