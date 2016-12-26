<?php namespace Buchin\Bing;

use Buchin\Bing\Bing;
use Symfony\Component\DomCrawler\Crawler;

/**
* Bing
*/
class Web extends Bing
{
	public $prefix = 'search';

	public function getContent()
	{
		$response = $this->client->get($this->prefix, [
			'query' => [
					'q' => trim($this->fullQuery),
					'format' => 'rss'
				]
			]);

		if($response->getStatusCode() != 200){
			return false;
		}

		$body = $this->hookBefore((string)$response->getBody());

		return $body;
	}

	public function hookBefore($content)
	{
		return str_replace('pubDate>', 'pubdate>', $content);
	}

	public function parseContent()
	{
		$results = [];

		$this->crawler = new Crawler($this->content);

		$crawler = $this->crawler->filterXPath('//channel/item');

		foreach ($crawler as $node) {
			$c = new Crawler($node);

			$result = [
				'title' => $c->filter('title')->text(),
				'link' => $c->filter('link')->text(),
				'description' => $c->filter('description')->text(),
				'pubdate' => $c->filter('pubdate')->text(),
			];

			$results[] = $result;
		}

		return $results;
	}
}