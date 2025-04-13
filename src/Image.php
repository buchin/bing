<?php namespace Buchin\Bing;

use Buchin\Bing\Bing;
use Symfony\Component\DomCrawler\Crawler;
/**
 * Image
 */
class Image extends Bing
{
    public $prefix = "images/async";
    public $raw_images, $related;
    public $images = [];

    public function getContent()
    {
        $response = $this->client->get($this->prefix, [
            "query" => [
                "q" => trim($this->fullQuery),
                // 'adlt' => 'strict',
                "qft" => $this->filters,
            ],
        ]);

        if ($response->getStatusCode() != 200) {
            return false;
        }

        return (string) $response->getBody();
    }

    public function parseContent()
    {
        $results = $this->crawler->filter(".imgpt")->each(function ($node) {
            $json = @json_decode($node->filter("a.iusc")->attr("m"));

            if(is_null($json)){
                return null;
            }

            try {
                $size = $node->filter("div.img_info span.nowrap")->html();
            } catch (\InvalidArgumentException $e) {
                // I guess its InvalidArgumentException in this case
                $size = "0 x 0";
            }

            try {
                return [
                    "url" => $json->murl,
                    "mediaurl" => $json->murl,
                    "link" => $json->purl,
                    "title" => str_replace(["", "", " ..."], "", $json->t),
                    "thumbnail" => $json->turl,
                    "size" => $size,
                    "desc" => $json->desc,
                ];
            }
            catch (\Exception $e){
                return [];
            }
        });

        $results = collect($results)
            ->filter()
            ->unique("url")
            ->toArray();

        $related = $this->crawler
            ->filter("a > div.cardInfo > div > strong")
            ->each(function (Crawler $node, $i) {
                return $node->text();
            });

        $results = $this->postProcessImage($results);

        $this->images = $results;
        $this->related = $related;

        return $results;
    }

    public function postProcessImage($raw_images)
    {
        $images = [];
        foreach ($raw_images as $raw_image) {
            $image = $this->postProcessSingleImage($raw_image);

            $images[] = $image;
        }

        return $images;
    }

    public function postProcessSingleImage($raw_image, $delimiter = "·")
    {
        $urlparts = @explode($delimiter, $raw_image["url"]);

        $raw_image["filetype"] = $urlparts[count($urlparts)-1];
        $raw_image["filetype"] =
            $raw_image["filetype"] == "jpeg" ? "jpg" : $raw_image["filetype"];
        $raw_image["filetype"] =
            $raw_image["filetype"] == "animatedgif"
                ? "gif"
                : $raw_image["filetype"];

        // Check if filetype is successfully extracted
        if(str($raw_image["filetype"])->length() > 5){
            // filetype length is usually 3-5 chars, lets find it from URL;
            $raw_image["filetype"] = str($raw_image['url'])->afterLast('.')->before('?')->toString();
        }

        try {
            $sizeParts = explode($delimiter, $raw_image["size"]);
            $dimensions = isset($sizeParts[0]) ? explode("×", $sizeParts[0]) : [];

            $raw_image["width"] = isset($dimensions[0]) ? (int) $dimensions[0] : 0;
            $raw_image["height"] = isset($dimensions[1]) ? (int) $dimensions[1] : 0;
        }
        catch (\Exception $e)
        {
            $raw_image["width"] = 0;
            $raw_image["height"] = 0;
        }

        $raw_image["domain"] = parse_url($raw_image["link"], PHP_URL_HOST);

        return $raw_image;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function getRelated()
    {
        return $this->related;
    }
}
