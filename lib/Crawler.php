<?php


class Crawler {

	public $doc = null;

	public $finder = null;

	public function __construct (string $url = '') {
		$this->finder = $this->getFinder($url);

		if ($this->finder)
			$this->doc = $this->finder->document;
	}

	private function getFinder (string $url) {
		$rawHTML = file_get_contents($url);

		if ($rawHTML == '')
			return false;

		$doc = new DOMDocument();
		$doc->loadHTML($rawHTML);

		return new DomXPath($doc);
	}

	public function getElementByClass (string $class, $contextNode = false) {
		$result = null;

		if ($contextNode) {
			$result = $this->finder->query(".//*[contains(@class, '$class')]", $contextNode);
		} else {
			$result = $this->finder->query("//*[contains(@class, '$class')]");
		}

		$elements = [];
		foreach ($result as $node) {
			if (in_array($class, explode(' ', $node->getAttribute('class')))) {
				$elements[] = $node;
			}
		}

		if (count($elements) === 1)
			$elements = $elements[0];

		return $elements;
	}
}