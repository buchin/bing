<?php namespace Buchin\Bing;

use Buchin\Bing\Bing;
use Symfony\Component\DomCrawler\Crawler;
/**
 * Image
 */
class Video extends Bing
{
    public $prefix = "videos/search";

    public function getContent()
    {
        $q = http_build_query([
            "q" => trim($this->fullQuery),
            "qft" => "+filterui:msite-youtube.com" . $this->filters,
        ]);

        $url = "https://www.bing.com/videos/search?" . $q . "&FORM=HDRSC3";
        $response = @file_get_contents($url);

        if (!$response) {
            return false;
        }

        // let's get token
        preg_match_all('/IG:"(.*)",EventID/m', $response, $matches);

        // Print the entire match result
        $token = isset($matches[1][0]) ? $matches[1][0] : "";

        if (empty($token)) {
            return false;
        }

        $url =
            "https://www.bing.com/videos/async/rankedans?q=" .
            $q .
            "&mmasync=1&varh=VideoResultInfiniteScroll&vdpp=VideoResultAsync&count=35&first=0&IG=" .
            $token .
            "IID=vrpalis&SFX=4";

        $response = @file_get_contents($url);

        return $response;
    }

    public function parseContent()
    {
        $results = $this->crawler
            ->filter(".mc_fgvc_u")
            ->each(function (Crawler $node, $i) {
                $json_node = $node->filter('.vrhdata');
                $json = json_decode($json_node->attr("vrhm"), true);                
                $title = $json["vt"];

                $duration = $json["du"];

                $views = 0;

                try {
                    $views = $node->filter(".meta_vc_content")->text();
                } catch (\InvalidArgumentException $e) {
                }

                $item = [
                    "title" => $title,
                    "duration" => $duration,
                    "views" => $views,
                ];

                $link = $json["murl"];
                $query = parse_url($link, PHP_URL_QUERY);

                if ($query !== false) {
                    parse_str($query, $vquery);
                    if (!empty($vquery["v"])) {
                        $item["link"] =
                            "https://www.youtube.com/watch?v=" . $vquery["v"];
                        $item["videoid"] = $vquery["v"];
                        $item["thumbnail"] =
                            "https://i.ytimg.com/vi/" .
                            $vquery["v"] .
                            "/default.jpg";
                        $item["thumbnail_mq"] =
                            "https://i.ytimg.com/vi/" .
                            $vquery["v"] .
                            "/mqdefault.jpg";
                        $item["thumbnail_hq"] =
                            "https://i.ytimg.com/vi/" .
                            $vquery["v"] .
                            "/hqdefault.jpg";
                    }
                }

                return $item;
            });

        return $results;
    }
}
