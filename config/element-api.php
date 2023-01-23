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
                        'jsonUrl' => UrlHelper::url("api/homepage.json")
                    ];
                },
            ];
        },
        'api/studio.json' => function() {
            return [
                'elementType' => Entry::class,
                'criteria' => ['section' => 'studio', 'limit' => 1],
                'transformer' => function(Entry $entry) {

                    $panels = [];
                    foreach ($entry->studioIndustryExpPanels as $row){
                        $panels[] = [
                            'subTitle' => $row->subtitle,
                            'industryTitle' => $row->industryTitle,
                            'industryBody' => $row->industryBody
                        ];
                    };

                    return [
                        'metaTitle' => $entry->metaTitle,
                        'metaDescription' => $entry->metaDescription,
                        'metaImage' => $entry->metaImage,
                        'studioEyebrowText' => $entry->studioEyebrowText,
                        'studioTitle' => $entry->studioTitle,
                        'studioIntro' => $entry->studioIntro,
                        'studioCta' => $entry->studioCta,
                        'studioMoreAbout' => $entry->studioMoreAbout,
                        'studioMoreAboutText' => $entry->studioMoreAboutText,
                        'studioIndustryExpTitle' => $entry->studioIndustryExpTitle,
                        'studioIndustryExpIntro' => $entry->studioIndustryExpIntro,
                        'studioIndustryExpPanels' => $panels,
                        'jsonUrl' => UrlHelper::url("api/studio.json")
                    ];
                },
            ];
        },
       
    ]
];