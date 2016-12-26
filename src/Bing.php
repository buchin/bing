<?php namespace Buchin\Bing;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
/**
* Bing
*/
class Bing
{
	public $fullQuery = '';
	public $textScraper = null;
	public $baseUrl = 'http://www.bing.com/';
	public $client = null;
	public $crawler = null;
	public $content = null;
	public $filters = null;


	function __construct()
	{
		$this->client = new Client([
			'base_uri' => $this->baseUrl,
			'verify' => false,
			]);
	}

	public function scrape($query, $hack = '', $options = [])
	{
		$this->fullQuery = trim($query . ' ' . trim($hack));
		$this->filters = $this->buildOptions($options);

		if(false == ($this->content = $this->getContent())){
			return false;
		}

		$results = $this->parseContent();

		return $results;
	}

	public function getContents()
	{
		
	}

	public function parseContent()
	{
		
	}

	public function buildOptions($options)
	{
		$filters = [];

		foreach ($options as $key => $value) {
			$filters[] = '+filterui:' . $key . '-' . $value;
		}

		return trim(implode('', $filters));
	}
}