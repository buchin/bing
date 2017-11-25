<?php namespace Buchin\Bing;

use Buchin\Bing\Bing;
use Symfony\Component\DomCrawler\Crawler;
/**
* Image
*/
class Video extends Bing
{
	public $prefix = 'videos/search';

	public function getContent()
	{
		$q = http_build_query([
			'q' => trim($this->fullQuery),
			'qft' => '+filterui:msite-youtube.com' . $this->filters
		]);

		$url = 'https://www.bing.com/videos/search?' . $q . '&FORM=HDRSC3';
		$response = @file_get_contents($url);

		if(!$response){
			return false;
		}

		return $response;
	}

	public function parseContent()
	{
		$this->crawler = new Crawler($this->content);

		$results = $this->crawler->filter('td.resultCell')->each(function(Crawler $node, $i){

			$title = ($node->filter('div.title span')->count()) ? $node->filter('div.title span')->attr('title') : strip_tags($node->filter('div.title')->html());

			$duration = ($node->filter('span.duration')->count()) ? $node->filter('span.duration')->text() : '0:00';

			$views = filter_var($node->filter('span')->last()->text(), FILTER_SANITIZE_NUMBER_INT);

			$item = [
				'title' => $title,
				'duration' => $duration,
				'views' => $views,
			];

			$link = $node->filter('a')->attr('href');
			$query = parse_url($link, PHP_URL_QUERY);

            if ($query !== false) {
                parse_str($query, $vquery);
                if (!empty($vquery['v'])) {
                    $item['link']          = "https://www.youtube.com/watch?v=".$vquery['v'];
                    $item['videoid']       = $vquery['v'];
                    $item['thumbnail']     = "https://i.ytimg.com/vi/" . $vquery['v'] . "/default.jpg";
                    $item['thumbnail_mq']  = "https://i.ytimg.com/vi/" . $vquery['v'] . "/mqdefault.jpg";
                    $item['thumbnail_hq']  = "https://i.ytimg.com/vi/" . $vquery['v'] . "/hqdefault.jpg";
                }
            }

			return $item;
		});

		return $results;
	}
}