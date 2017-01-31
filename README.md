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
$images = $imageScraper->scrape('telolet om', '', ['image_size' => 'extra_large']);

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

#### (new) Available options for image:
```php
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

