<?php

return [
    'allowed_domains' => array_values(array_filter(array_map('trim', explode(',', env('VIDEO_ALLOWED_EMBED_DOMAINS', 'partner1.com,partner2.com,youtube.com,youtu.be,vimeo.com,player.vimeo.com'))))),

    'allowed_api_hosts' => array_values(array_filter(array_map('trim', explode(',', env('VIDEO_ALLOWED_API_HOSTS', 'api.partner1.com,api.partner2.com'))))),

    'default_status' => 'draft',

    'source_map' => [
        'youtube.com' => 'YouTube',
        'youtu.be' => 'YouTube',
        'vimeo.com' => 'Vimeo',
        'player.vimeo.com' => 'Vimeo',
        'partner1.com' => 'Partner 1',
        'partner2.com' => 'Partner 2',
    ],
];
