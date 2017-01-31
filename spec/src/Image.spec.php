<?php
use Buchin\Bing\Image;

describe('Buchin\Bing\Image', function(){
	given('scraper', function(){
		return new Image;
	});

	describe('scrape()', function(){
		it('scrapes data from bing image', function(){
			$images = $this->scraper->scrape('telolet om', '', ['image_size' => 'extra_large', 'people' => 'just_faces']);

			expect($images)->not->toBeEmpty();
		});
	});
});