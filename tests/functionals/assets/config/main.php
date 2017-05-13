<?php

return [
    'id' => 'app-test',
    'basePath' => __DIR__ . '/..',
    'vendorPath' => __DIR__ . '/../../../../vendor',
    'aliases' => [
        '@bupy7/cropbox' => __DIR__ . '/../../../../src',
    ],
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'wefJDF8sfdsfSDefwqdxj9oq',
            'scriptFile' => __DIR__ . '/index.php',
            'scriptUrl' => '/index.php',
        ],
        'assetManager' => [
            'basePath' => '@app/assets',
            'baseUrl' => '/',
        ],
    ]
];
