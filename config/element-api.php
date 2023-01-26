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

                    $bodyBlocks = [];
                    foreach ($entry->pageBlocks->all() as $block) {
                        switch ($block->type->handle) {
                            case 'carousel':
                                $carouselPanels = [];
                                foreach ($block->carouselItems->all() as $row){
                                    $carouselPanels[] = [
                                        'carouselImageUrl' => $row->carouselImageUrl,
                                        'carouselTitle' => $row->carouselTitle,
                                    ];
                                }
                                $bodyBlocks[] = [
                                    'uid' => $block->uid,
                                    'blockType' => 'carousel',
                                    'carouselTitle' => $block->carouselTitle,
                                    'carouselItems' => $carouselPanels,
                                ];
                            break; 
                            case 'faqs':
                                $faqRows = [];
                                foreach($block->faqs->all() as $row){
                                    $faqRows[] = [
                                        'question' => $row->question,
                                        'answer' => $row->answer,
                                    ];
                                }
                                $bodyBlocks[] = [
                                    'uid' => $block->uid,
                                    'blockType' => 'faq',
                                    'faqHeading' => $block->faqHeading,
                                    'faqLeadtext' => $block->faqLeadtext,
                                    'faqs' => $faqRows
                                ];
                            break;
                            case 'gallerygrid':
                                $gallery = [];
                                foreach($block->galleryGrid->all() as $row){
                                    $gallery[] = [
                                        'src' => $row->src,
                                        'original' => $row->original,
                                        'width' => $row->width,
                                        'height' => $row->height,
                                        'caption' => $row->caption
                                    ];
                                }
                                $bodyBlocks[] = [
                                    'uid' => $block->uid,
                                    'blockType' => 'gallerygrid',
                                    'galleryGridTitle' => $block->galleryGridTitle,
                                    'gallery' => $gallery
                                ];
                            break;                        
                            case 'imageSlider':
                                $SuperTableRows = [];
                                foreach ($block->sliderStage->all() as $row){
                                    $SuperTableRows[] = [
                                        'textSub' => $row->textSub,
                                        'textHeading' => $row->textHeading,
                                        'textBackground' => $row->textBackground,
                                        'slideImage' => $row->slideImage,
                                        'slideColor' => $row->slideColor->value,
                                    ];
                                }
                                $bodyBlocks[] = [
                                    'uid' => $block->uid,
                                    'blockType' => 'imageSlider',
                                    'sliderTitle' => $block->sliderTitle,
                                    'sliderStage' => $SuperTableRows,
                                ];
                            break;
                            case 'video':
                                $bodyBlocks[] = [
                                    'uid' => $block->uid,
                                    'blockType' => 'video',
                                    'videoTitle' => $block->videoTitle,
                                    'videoEmbedCode' => $block->videoEmbedCode,
                                ];
                            break;
                           
                        }
                    }

                    return [
                        'title' => $entry->title,
                        'homeTitle' => $entry->homeTitle,
                        'homeSubTitle' => $entry->homeSubTitle,
                        'pageBlocks' => $bodyBlocks,
                        'jsonUrl' => UrlHelper::url("api/homepage.json")
                    ];
                },
            ];
        },

        'api/contact.json' => function() {
            return [
                'elementType' => Entry::class,
                'criteria' => ['section' => 'contact'],
                'transformer' => function(Entry $entry) {

                    
                    $contactFaqs = [];
                    foreach ($entry->contactFaqs as $row){
                        $contactFaqs[] = [
                            'question' => $row->question,
                            'answer' => $row->answer
                        ];
                    };


                    return [
                        'title' => $entry->title,
                        'contactTitle' => $entry->contactTitle,
                        'contactIntro' => $entry->contactIntro,
                        'contactMapEmbed' => $entry->contactMapEmbed,
                        'contactFaqs' => $contactFaqs,
                        'jsonUrl' => UrlHelper::url("api/contact.json")
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
        'api/articles.json' => function() {
            \Craft::$app->response->headers->set('Access-Control-Allow-Origin', '*');
            return [
                'elementType' => Entry::class,
                'criteria' => ['section' => 'articles'],
                'elementsPerPage' => 10,
                'transformer' => function(Entry $entry) {

                    // $articleCategory = $entry->category->one()->id;
                    // $relatedArticles = Entry::find()
                    //     ->section('articles')
                    //     ->limit(10)
                    //     ->all();

                    return [
                        'slug' => $entry->slug,
                        'title' => $entry->title,
                        'articleTitle' => $entry->articleTitle,
                        'articleExcerpt' => $entry->articleExcerpt,
                        // 'catId' => $articleCategory,
                        'jsonUrl' => UrlHelper::url("/api/articles/{$entry->slug}.json"),
                    
                    ];
                },
            ];
        }, 
        'api/articles/<slug:{slug}>.json' => function($slug) {
            return [
                'elementType' => Entry::class,
                'criteria' => ['slug' => $slug],
                'one' => true,
                'transformer' => function(Entry $entry) {
                    
                    // $bodyBlocks = [];
                    // foreach ($entry->pageBlocks->all() as $block) {
                    //     switch ($block->type->handle) {
                    //         case 'hero':
                    //             $bodyBlocks[] = [
                    //                 'blockType' => 'hero',
                    //                 'uid' => $block->uid,
                    //                 'eyebrow' => $block->eyebrow,
                    //                 'heading' => $block->heading,
                    //                 'subHeading' => $block->subHeading
                    //             ];
                    //             break;
                    //         case 'header':
                    //         $bodyBlocks[] = [
                    //             'uid' => $block->uid,
                    //             'blockType' => 'header',
                    //             'headline' => $block->headline,
                    //             ];
                    //             break;
                    //         case 'faq':
                    //             $faqRows = [];
                    //             foreach($block->faqs->all() as $row){
                    //                 $faqRows[] = [
                    //                     'question' => $row->question,
                    //                     'answer' => $row->answer,
                    //                 ];
                    //             }
                    //             $bodyBlocks[] = [
                    //                 'uid' => $block->uid,
                    //                 'blockType' => 'faq',
                    //                 'faqHeading' => $block->faqHeading,
                    //                 'faqLeadtext' => $block->faqLeadtext,
                    //                 'faqs' => $faqRows
                    //             ];
                    //             break;
                            
                    //         // // case 'speakers':
                    //         // //     // $selectedSpeakers = [];
                    //         // //     $relatedCat = $block->speakerCategory->one()->id;
                                
                    //         // //     // $blockSpeakers = Entry::find()
                    //         // //     //     ->section('speakers')
                    //         // //     //     ->relatedTo($relatedCat)
                    //         // //     //     ->limit(10)
                    //         // //     //     ->all();

                                
                    //         // //     // $selectedSpeakers = [];
                    //         // //     //     foreach($blockSpeakers as $spkr){
                    //         // //     //     $selectedSpeakers[] = [
                    //         // //     //         'speakerName' => $spkr->speakerName
                    //         // //     //     ];
                    //         // //     // }

                    //         // //     $bodyBlocks[] = [
                    //         // //         'heading' => $block->heading,
                    //         // //         'speakersIntro' => $block->speakersIntro,
                    //         // //         // 'speakers' => $selectedSpeakers
                    //         // //     ];
                    //         // //     break;
                    //         case 'video':
                    //             $bodyBlocks[] = [
                    //                 'uid' => $block->uid,
                    //                 'blockType' => 'video',
                    //                 'videoTitle' => $block->videoTitle,
                    //                 'videoEmbedCode' => $block->videoEmbedCode,
                    //             ];
                    //             break;
                    //         case 'imageSlider':
                    //             $SuperTableRows = [];
                    //             foreach ($block->sliderMatrix->all() as $row){
                    //                 $SuperTableRows[] = [
                    //                     'textSub' => $row->textSub,
                    //                     'textHeading' => $row->textHeading,
                    //                     'textBackground' => $row->textBackground,
                    //                     'slideImage' => $row->slideImage,
                    //                     'slideColor' => $row->slideColor->value,
                    //                 ];
                    //             }
                    //             $bodyBlocks[] = [
                    //                 'uid' => $block->uid,
                    //                 'blockType' => 'imageSlider',
                    //                 'sliderTitle' => $block->sliderTitle,
                    //                 'sliderMatrix' => $SuperTableRows,
                    //             ];
                    //             break;
                    //         // case 'text':
                    //         //     $bodyBlocks[] = [
                    //         //         'uid' => $block->uid,
                    //         //         'blockType' => 'text',
                    //         //         'headline' => $block->headline,
                    //         //         'articleBody' => $block->articleBody,
                    //         //     ];
                    //         //     break;
                    //         // case 'textVisual':

                    //         //     $TVButtons = [];
                    //         //     foreach ($block->textVisualButtons->all() as $row){
                    //         //         $TVButtons[] = [
                    //         //             'linkId' => $row->linkId,
                    //         //             'linkText' => $row->linkText,
                    //         //             'linkUrl' => $row->linkUrl,
                    //         //             'isExternal' => $row->target
                    //         //         ];
                    //         //     }

                    //         //     $bodyBlocks[] = [
                    //         //         'uid' => $block->uid,
                    //         //         'blockType' => 'textVisual',
                    //         //         'title' => $block->textVisualTitle,
                    //         //         'articleBody' => $block->textVisualContent,
                    //         //         'image' => $block->textVisualImage,
                    //         //         'buttons' => $TVButtons,
                    //         //     ];
                    //         //     break;
                    //     }
                    // }

                    return [
                        'slug' => $entry->slug,
                        'title' => $entry->title,
                        'articleTitle' => $entry->articleTitle,
                        'articleTypePostDate' => $entry->postDate->format(\DateTime::ATOM),
                        'articleType' => $entry->articleType,
                        'articleExcerpt' => $entry->articleExcerpt,
                        'articleImageUrl' => $entry->articleImageUrl,
                        'articleImageAlt' => $entry->articleImageAlt,
                        'articleVideoEmbed' => $entry->articleVideoEmbed,
                        'articleContent' => $entry->articleContent,
                        'jsonUrl' => UrlHelper::url("/api/articles/{$entry->slug}.json"),
                    ];
                },
            ];
        },     
    ]
];