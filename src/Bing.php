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

	public $query_filters = [
        'image' => [
        	'image_size' => [
	            'all' => '',
	            'small' => '+filterui:imagesize-small',
	            'medium' => '+filterui:imagesize-medium',
	            'large' => '+filterui:imagesize-large',
	            'extra_large' => '+filterui:imagesize-wallpaper',
	        ],
	        'color' => [
	            'all' => '',
	            'color_only' => '+filterui:color2-color',
	            'black_and_white' => '+filterui:color2-bw',
	            'red' => '+filterui:color2-FGcls_RED',
	            'orange' => '+filterui:color2-FGcls_ORANGE',
	            'green' => '+filterui:color2-FGcls_GREEN',
	        ],
	        'type' => [
	            'all' => '',
	            'photograph' => '+filterui:photo-photo',
	            'clipart' => '+filterui:photo-clipart',
	            'line_drawing' => '+filterui:photo-linedrawing',
	            'animated_gif' => '+filterui:photo-animatedgif',
	            'transparent' => '+filterui:photo-transparent',
	        ],
	        'layout' => [
	            'all' => '',
	            'square' => '+filterui:aspect-square',
	            'wide' => '+filterui:aspect-wide',
	            'tall' => '+filterui:aspect-tall',
	        ],
	        'people' => [
	            'all' => '',
	            'just_faces' => '+filterui:face-face',
	            'head_and_shoulders' => '+filterui:face-portrait',
	        ],
	        'date' => [
	            'all' => '',
	            'past_24_hours' => '+filterui:age-lt1440',
	            'past_week' => '+filterui:age-lt10080',
	            'past_month' => '+filterui:age-lt43200',
	            'past_year' => '+filterui:age-lt525600',
	        ],
	        'license' => [
	            'all' => '',
	            'all_creative_commons' => '+filterui:licenseType-Any',
	            'public_domain' => '+filterui:license-L1',
	            'free_to_share_and_use' => '+filterui:license-L2_L3_L4_L5_L6_L7',
	            'free_to_share_and_use_commercially' => '+filterui:license-L2_L3_L4',
	            'free_to_modify_share_and_use' => '+filterui:license-L2_L3_L5_L6',
	            'free_to_modify_share_and_use_commercially' => '+filterui:license-L2_L3',
	        ]
        ]
    ];


	function __construct()
	{
		$this->client = new Client([
			'base_uri' => $this->baseUrl,
			'verify' => false,
			'http_errors' => false
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
		$type = strtolower(str_replace('Buchin\Bing\\', '', get_class($this)));

		$filters = [];

		foreach ($options as $key => $value) {
			$filters[] = $this->query_filters[$type][$key][$value];
		}

		return trim(implode('', $filters));
	}
}