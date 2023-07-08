<?php

use craft\elements\Entry;
use craft\elements\Category;
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
                            case 'testimonialSlider':
                                $SuperTableRows = [];
                                foreach ($block->testimonialCards->all() as $row){
                                    $SuperTableRows[] = [
                                        'testimonial' => $row->testimonial,
                                        'testimonialImage' => $row->testimonialImage,
                                        'testimonialClient' => $row->testimonialClient
                                    ];
                                }
                                $bodyBlocks[] = [
                                    'uid' => $block->uid,
                                    'blockType' => 'testimonialSlider',
                                    'testimonialsId' => $block->testimonialsId,
                                    'testimonialCards' => $SuperTableRows,
                                ];
                            break;
                            case 'quote':
                                $bodyBlocks[] = [
                                    'uid' => $block->uid,
                                    'blockType' => 'quote',
                                    'quoteBackgroundColor' => $block->quoteBackgroundColor,
                                    'quoteTitle' => $block->quoteTitle,
                                    'quoteImageUrl' => $block->quoteImageUrl,
                                    'quoter' => $block->quoter,
                                    'company' => $block->company,
                                ];
                            break;
                            case 'textBlock':
                                $bodyBlocks[] = [
                                    'uid' => $block->uid,
                                    'blockType' => 'textBlock',
                                    'textTitle' => $block->textTitle,
                                    'textBoldTitle' => $block->textBoldTitle,
                                    'textContent' => $block->textContent,
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
                'serializer' => 'array',
                'elementType' => Entry::class,
                'criteria' => ['section' => 'articles', 'orderBy' => 'postDate desc'],
                'cache' => 'PT1M', // one minute
                'transformer' => function(Entry $entry) {

                    $categories = []; 
                    $articleCats = $entry->articleCategory->all();
                    foreach ($articleCats as $cat){
                        $categories[] = [
                            'title' => $cat->title,
                            'slug' => $cat->slug,
                        ];
                    }

                    $primaryCat = $entry->articleCategory->all(); 

                    return [
                        'slug' => $entry->slug,
                        'title' => $entry->title,
                        'articleTitle' => $entry->articleTitle,
                        'articleExcerpt' => $entry->articleExcerpt,
                        'articleCategories' => $categories, 
                        'articleFeaturedImage' => $entry->articleFeaturedImage,
                        'articleImageUrl' => $entry->articleImageUrl,
                        'articleImageAlt' => $entry->articleImageAlt,
                        // 'relatedArticles' => $relatedArticles,
                        'articleTypePostDate' => $entry->postDate->format(\DateTime::ATOM),
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
                    
                    $relatedArticles = [];
                    foreach ($entry->relatedArticles->all() as $article) {
                        $relatedArticles[] = [
                            'title' => $article->title
                        ];
                    }

                    $categories = []; 
                    $articleCats = $entry->articleCategory->all();
                    foreach ($articleCats as $cat){
                        $categories[] = [
                            'title' => $cat->title,
                            'slug' => $cat->slug,
                        ];
                    }

                    return [
                        'slug' => $entry->slug,
                        'title' => $entry->title,
                        'articleTitle' => $entry->articleTitle,
                        'articleTypePostDate' => $entry->postDate->format(\DateTime::ATOM),
                        'articleType' => $entry->articleType,
                        'articleCategories' => $categories, 
                        'articleExcerpt' => $entry->articleExcerpt,
                        'articleFeaturedImage' => $entry->articleFeaturedImage,
                        'articleImageUrl' => $entry->articleImageUrl,
                        'articleImageAlt' => $entry->articleImageAlt,
                        'articleVideoEmbed' => $entry->articleVideoEmbed,
                        'articleContent' => $entry->articleContent,
                        'relatedArticles' => $relatedArticles,
                        'jsonUrl' => UrlHelper::url("/api/articles/{$entry->slug}.json"),
                    ];
                },
            ];
        },  
        'api/category/<slug:{slug}>.json' => function($slug) {
            \Craft::$app->response->headers->set('Access-Control-Allow-Origin', '*');
           
            return [
                'serializer' => 'array',
                'elementType' => Category::class,
                'criteria' => ['slug' => $slug],
                'cache' => 'PT1M', // one minute
                'transformer' => function(Category $category) {
                    $entries = Entry::find()
                        ->section('articles') // Replace with the handle of your section
                        ->relatedTo($category)
                        ->orderBy('postDate desc')
                        ->all();

                    $entryData = [];

                    foreach ($entries as $entry) {
                        $entryData[] = [
                            'title' => $entry->title,
                            'slug' => $entry->slug,
                            'articleTitle' => $entry->articleTitle,
                            'articleTypePostDate' => $entry->postDate->format(\DateTime::ATOM),
                            'articleExcerpt' => $entry->articleExcerpt,
                            'articleFeaturedImage' => $entry->articleFeaturedImage,
                            'articleImageUrl' => $entry->articleImageUrl,
                            'articleImageAlt' => $entry->articleImageAlt,
                            'url' => $entry->getUrl(),
                        ];
                    }

                    return [
                        'category' => [
                            'title' => $category->title,
                            'slug' => $category->slug,
                            'url' => $category->getUrl(),
                        ],
                        'entries' => $entryData,
                    ];

                },
            ];
        }
   
    ]
];