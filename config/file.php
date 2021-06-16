<?php

return [
    'acceptable' => [
        'image' => [
            'image/jpg'  => 'jpg',
            'image/png'  => 'png',
            'image/jpeg' => 'jpeg',
            'image/gif'  => 'gif',
        ],
        'collection' => [
            'image/jpg'  => 'jpg',
            'image/png'  => 'png',
            'image/jpeg' => 'jpeg',
            'image/gif'  => 'gif',
            'video/mp4' => 'video'
        ],
    ],
    'categories' => [
        'account_profile_image'  => [
            'name'        => 'account_profile_image',
            'type'        => 'image',
            'region'      => env('AWS_IMAGE_REGION'),
            'buckets'     => [
                env('AWS_IMAGE_BUCKET'),
            ],
            'size'        => [300, null],
            'thumbnails'  => [],
            'seed_prefix' => 'account_profile',
            'format'      => 'png',
            'local_thumb'  => 'thumbs/',
            'local_path'  => 'images/accounts/'
        ],
        'store_profile_image'  => [
            'name'        => 'account_profile_image',
            'type'        => 'image',
            'region'      => env('AWS_IMAGE_REGION'),
            'buckets'     => [
                env('AWS_IMAGE_BUCKET'),
            ],
            'size'        => [300, null],
            'thumbnails'  => [],
            'seed_prefix' => 'account_profile',
            'format'      => 'png',
            'local_thumb'  => 'thumbs/',
            'local_path'  => 'images/stores/'
        ],
        'admin_collection'  => [
            'name'        => 'admin_collection',
            'type'        => 'collection',
            'seed_prefix' => 'admin_collection',
        ],
        'store_collection'  => [
            'name'        => 'store_collection',
            'type'        => 'collection',
            'seed_prefix' => 'store_collection',
        ]

    ],
];
