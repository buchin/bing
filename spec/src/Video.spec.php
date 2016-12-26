<?php
use Buchin\Bing\Video;

describe('Buchin\Bing\Video', function(){
	given('scraper', function(){
		return new Video;
	});

	describe('scrape()', function(){
		it('scrapes data from bing video', function(){
			$videos = $this->scraper->scrape('telolet om');
			expect($videos)->not->toBeEmpty();
		});
	});
});