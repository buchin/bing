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
        $results = $this->crawler
            ->filter(".imgpt")
            ->each(function (Crawler $node, $i) {
                $json = @json_decode($node->filter("a.iusc")->attr("m"));
                try {
                    $size = $node->filter("div.img_info span.nowrap")->html();
                } catch (\InvalidArgumentException $e) {
                    // I guess its InvalidArgumentException in this case
                    $size = "0 x 0";
                }

                $raw_image = [
                    "mediaurl" => $json->murl,
                    "link" => $json->purl,
                    "title" => str_replace(["", "", " ..."], "", $json->t),
                    "thumbnail" => $json->turl,
                    "size" => $size,
                ];

                return $raw_image;
            });

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
        $raw_image["filetype"] = trim(
            @explode($delimiter, $raw_image["size"])[1]
        );
        $raw_image["filetype"] =
            $raw_image["filetype"] == "jpeg" ? "jpg" : $raw_image["filetype"];
        $raw_image["filetype"] =
            $raw_image["filetype"] == "animatedgif"
                ? "gif"
                : $raw_image["filetype"];

        $raw_image["width"] = explode(
            " x ",
            @explode($delimiter, $raw_image["size"])[0]
        )[0];
        $raw_image["height"] = explode(
            " x ",
            @explode($delimiter, $raw_image["size"])[0]
        )[1];
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
