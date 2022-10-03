<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'W4 Cloudinary',
    'description' => 'W4 API implementation and support for Cloudinary.',
    'version' => '1.0.1',
    'category' => 'fe',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
            'cloudinary/cloudinary_php' => '1.19.0-1.19.99'
        ],
    ],
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'W4 Services GmbH',
    'author_email' => 'info@w-4.ch',
    'author_company' => 'W4 Services GmbH',
    'autoload' => [
        'psr-4' => [
            'W4Services\\W4Cloudinary\\' => 'Classes'
        ],
    ],
];
