# buchin/bing
Bing Scraper: text, image, video

## usage

### Text example

```php
<?php
use Buchin\Bing\Web;

$webScraper = new Web;
// first params required, second is optional hack
$results = $webScraper->scrape('makan nasi', 'filetype:pdf');

/* 
return array 

array(10) {
  [0]=>
  array(4) {
    ["title"]=>
    string(39) "Makan Nasi Sama Garam - leutikaprio.com"
    ["link"]=>
    string(73) "http://www.leutikaprio.com/main/media/sample/ngakak%20sejenak%20smple.pdf"
    ["description"]=>
    string(155) "Fanny Fredlina 1 Makan Nasi Sama Garam Hati-hati kalau ingin memberikan rayuan gombal. Apalagi yang rada lebay. Jangan sampai terjadi hal yang seperti ini."
    ["pubdate"]=>
    string(29) "Fri, 25 Nov 2016 17:33:00 GMT"
  }
...
*/
```

### Image example
```php
<?php
use Buchin\Bing\Image;

$imageScraper = new Image;
$images = $imageScraper->scrape('telolet om', '', ['imagesize' => 'wallpaper']);

/*
Contoh hasil:
[27]=>
  array(4) {
    ["mediaurl"]=>
    string(112) "http://www.radarpekalongan.com/wp-content/uploads/2016/06/Mengintip-Keseruan-Bocah-Pemburu-Telolet-di-Batang.jpg"
    ["link"]=>
    string(100) "http://www.radarpekalongan.com/24530/mengintip-keseruan-bocah-pemburu-telolet-klakson-bus-di-batang/"
    ["title"]=>
    string(68) "Mengintip Keseruan Bocah Pemburu 'Telolet' Klakson Bus di Batang ..."
    ["size"]=>
    string(19) "700 x 400 jpeg 81kB"
  }
*/
```

### Example video
```php
<?php
use Buchin\Bing\Video;

$videoScraper = new Video;
$videos = $videoScraper->scrape('om telolet om');

/* Contoh hasil:

[11]=>
  array(6) {
    ["title"]=>
    string(38) "TELOLET OM.... | NGABUL | RAMAE SEKALI"
    ["link"]=>
    string(43) "https://www.youtube.com/watch?v=x33TbaAzmw8"
    ["videoid"]=>
    string(11) "x33TbaAzmw8"
    ["thumbnail"]=>
    string(46) "https://i.ytimg.com/vi/x33TbaAzmw8/default.jpg"
    ["thumbnail_mq"]=>
    string(48) "https://i.ytimg.com/vi/x33TbaAzmw8/mqdefault.jpg"
    ["thumbnail_hq"]=>
    string(48) "https://i.ytimg.com/vi/x33TbaAzmw8/hqdefault.jpg"
  }
*/

```

