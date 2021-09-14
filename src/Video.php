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
            "https://www.bing.com/videos/asyncv2?q=" .
            $q .
            "&async=content&first=0&count=35&dgst=RowIndex_u9*ColumnIndex_u3*TotalWidth_u864*OrdinalPosition_u35*ThumbnailWidth_u260*HeroContainerWidth_u1125*HeroContainerHeight_u275*HeroOnPage_b0*SlidesGridOnPage_b0*arn_u3*ayo_u869*cry_u2729*&IID=video.1&SFX=2&IG=" .
            $token .
            "&CW=1385&CH=620&bop=88&form=QBFVBS";

        $response = @file_get_contents($url);

        return $response;
    }

    public function parseContent()
    {
        $results = $this->crawler
            ->filter(".dg_u .vrhdata")
            ->each(function (Crawler $node, $i) {
                $json = json_decode($node->attr("vrhm"), true);

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
