<?php namespace Buchin\Bing;

use Buchin\Bing\Bing;
use Symfony\Component\DomCrawler\Crawler;
/**
* Image
*/
class Image extends Bing
{
	public $prefix = 'images/search';

	public function getContent()
	{
		$response = $this->client->get($this->prefix, [
			'query' => [
				'q' => trim($this->fullQuery),
				// 'adlt' => 'strict',
				'qft' => $this->filters
			],
		]);

		if($response->getStatusCode() != 200){
			return false;
		}

		return (string)$response->getBody();
	}

	public function parseContent()
	{
		$this->crawler = new Crawler($this->content);

		$results = $this->crawler->filter('div.item')->each(function(Crawler $node, $i){
			$image = [
				'mediaurl' => $node->filter('a.thumb')->attr('href'),
				'link' => $node->filter('a.tit')->attr('href'),
				'title' => $node->filter('div.des')->html(),
				'size' => $node->filter('div.fileInfo')->html()
			];

			return $image;
		});

		return $results;
	}
}