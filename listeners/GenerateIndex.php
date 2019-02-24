<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;

class GenerateIndex
{
    public function handle(Jigsaw $jigsaw)
    {
        $articles = collect(
                $jigsaw->getCollection('articles')
                    ->map(function ($page) use ($jigsaw) {
                        return [
                            'title' => $page->title,
                            'link' => rightTrimPath($jigsaw->getConfig('baseUrl')) . $page->getPath(),
                            'snippet' => $page->getExcerpt(),
                        ];
                    })
                    ->values()
            );

        $journal = collect(
                $jigsaw->getCollection('journal')
                    ->map(function ($page) use ($jigsaw) {
                        return [
                            'title' => $page->title,
                            'link' => rightTrimPath($jigsaw->getConfig('baseUrl')) . $page->getPath(),
                            'snippet' => $page->getExcerpt(),
                        ];
                    })
                    ->values()
            );

        $dataForIndex = $articles->merge($journal);

        file_put_contents($jigsaw->getDestinationPath() . '/index.json', json_encode($dataForIndex));
    }
}
