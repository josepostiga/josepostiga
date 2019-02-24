<?php

return [
    'baseUrl' => 'http://josepostiga.test',
    'production' => false,
    'siteName' => 'José Postiga / Senior Backend Developer',
    'siteDescription' => 'This is my personal blog',
    'siteAuthor' => 'José Postiga',

    // collections
    'collections' => [
        'articles' => [
            'author' => 'José Postiga', // Default author, if not provided in a post
            'sort' => '-date',
            'path' => 'blog/{filename}',
        ],
        'journal' => [
            'author' => 'José Postiga', // Default author, if not provided in a post
            'sort' => '-date',
            'path' => 'journal/{filename}',
        ],
    ],

    // helpers
    'getDate' => function ($page) {
        return Datetime::createFromFormat('U', $page->date);
    },
    'getExcerpt' => function ($page, $length = 255) {
        $content = $page->excerpt ?? $page->getContent();
        $cleaned = strip_tags(
            preg_replace(['/<pre>[\w\W]*?<\/pre>/', '/<h\d>[\w\W]*?<\/h\d>/'], '', $content),
            '<code>'
        );

        $truncated = substr($cleaned, 0, $length);

        if (substr_count($truncated, '<code>') > substr_count($truncated, '</code>')) {
            $truncated .= '</code>';
        }

        return strlen($cleaned) > $length
            ? preg_replace('/\s+?(\S+)?$/', '', $truncated) . '...'
            : $cleaned;
    },
    'isActive' => function ($page, $path) {
        return ends_with(trimPath($page->getPath()), trimPath($path));
    },
];
