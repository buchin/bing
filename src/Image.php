<?php namespace Buchin\Bing;

use Buchin\Bing\Bing;
use Symfony\Component\DomCrawler\Crawler;
/**
* Image
*/
class Image extends Bing
{
	public $prefix = 'images/async';

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

		$results = $this->crawler->filter('.imgpt')->each(function(Crawler $node, $i){
			$json = @json_decode($node->filter('a.iusc')->attr('m'));

			$image = [
				'mediaurl' => $json->murl,
				'link' => $json->purl,
				'title' => str_replace(['', '', ' ...'], '', $json->t),
				'thumbnail' => $json->turl,
				'size' => $node->filter('div.img_info span.nowrap')->html()
			];

			return $image;
		});

		return $results;
	}
}