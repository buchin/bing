<?php
/**
 * replace - with desired text
 */
describe('0.1.0 - Feature: Scrape Text', function(){
	context('User story:', function(){
		describe('As a user', function(){});
		describe('I want to scrape bing text', function(){});
		describe('So I can create dummy relevant text', function(){});
	});
	context('Scenario:', function(){
		given('textScraper', function(){
			return new Buchin\Bing\Web;
		});

		given('query', function(){
			return 'makan nasi';
		});

		given('hack', function(){
			return 'filetype:pdf';
		});

		describe('User calls bing text search method', function(){
			context('when hack is present', function(){
				it('combines search query with its hack', function(){

					$this->textScraper->scrape($this->query, $this->hack);

					expect($this->textScraper->fullQuery)->toBe('makan nasi filetype:pdf');
				});
			});
			context('when result is empty', function(){
				it('returns empty array', function(){
					allow($this->textScraper)->toReceive('scrape')->andReturn([]);
					$texts = $this->textScraper->scrape($this->query, $this->hack);
					expect($texts)->toBeEmpty();
				});
			});

			context('when contains results', function(){
				it('returns array of results', function(){
					$texts = $this->textScraper->scrape($this->query, $this->hack);
					expect($texts)->toBeA('array');
					expect($texts)->not->toBeEmpty();
				});
			});
		});
	});
});