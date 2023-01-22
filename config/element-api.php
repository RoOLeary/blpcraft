<?php

use craft\elements\Entry;
use craft\helpers\UrlHelper;

return [
    'endpoints' => [
        'api/homepage.json' => function() {
            return [
                'elementType' => Entry::class,
                'criteria' => ['section' => 'homepage'],
                'transformer' => function(Entry $entry) {
                    return [
                        'title' => $entry->title,
                        'homeTitle' => $entry->homeTitle,
                        'homeSubTitle' => $entry->homeSubTitle,
                        'url' => $entry->url,
                        'jsonUrl' => UrlHelper::url("homepage.json")
                    ];
                },
            ];
        },
       
    ]
];