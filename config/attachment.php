<?php

return [
    'user_model' => '\\App\\Models\\User',
    'user_table' => 'users',
    'user_key_name' => 'id',
    'user_key_type' => 'int',

    'attachments_dir' => 'attachments', // relative path from Storage
    'max_size' => 102400, // 100MB. Don't forget to adjust php.ini and nginx.conf
    'allowed_extensions' => [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'svg',
        'webp',
        'mp4',
        'webm',
        'mp3',
        'wav',
        'ogg',
        'pdf',
        'doc',
        'docx',
        'odt',
        'xls',
        'xlsx',
        'ods',
        'ppt',
        'pptx',
        'odp',
        'zip',
        'rar',
        'json',
        'geojson',
        'txt',
    ],

    'write_access_roles' => ['admin', 'operator'],
];
