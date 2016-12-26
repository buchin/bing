<?php
use Buchin\Bing\Image;

describe('Buchin\Bing\Image', function(){
	given('scraper', function(){
		return new Image;
	});

	describe('scrape()', function(){
		it('scrapes data from bing image', function(){
			$images = $this->scraper->scrape('telolet om', '', ['imagesize' => 'wallpaper']);
			expect($images)->not->toBeEmpty();
		});
	});
});