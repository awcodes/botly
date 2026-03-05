<?php

declare(strict_types=1);

// translations for Awcodes/Botly
return [
    'title' => 'Manage robots.txt',
    'navigation' => [
        'label' => 'Robots Manager',
    ],
    'form' => [
        'rules' => [
            'label' => 'Rules',
            'fields' => [
                'user_agent' => 'User-Agent',
                'directive' => 'Directive',
                'disallow' => 'Disallow',
                'allow' => 'Allow',
                'crawl_delay' => 'Crawl-delay',
                'clean_param' => 'Clean-param',
                'path' => 'Path',
            ],
            'add' => 'Add Rule',
        ],
        'sitemaps' => [
            'label' => 'Sitemaps',
            'field' => 'Sitemap URL',
            'add' => 'Add Sitemap URL',
        ],
        'ai_crawlers' => [
            'label' => 'Block AI Crawlers',
        ],
        'submit' => 'Save',
        'callout' => [
            'label' => 'Existing File Found',
            'description' => "We've detected an existing robots.txt file in your public directory. For these changes to take effect, you'll need to delete the existing file or rename it.",
            'delete' => 'Delete File',
            'delete_success' => 'robots.txt file deleted successfully.',
            'rename' => 'Rename File to robots-bak.txt',
            'rename_success' => 'robots.txt file renamed successfully.',
        ],
    ],
    'export' => [
        'label' => 'Export Robots.txt',
        'success' => 'Robots.txt exported successfully to public/robots.txt.',
    ],
];
