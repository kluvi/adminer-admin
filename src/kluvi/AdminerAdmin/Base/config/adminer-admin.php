<?php
return [
    /**
     * Route, where adminer should be available.
     * If you don't want to use route from provider, set it to empty string or false
     */
    'route' => '/adminer-admin',

    /**
     * Directory, where Adminer Editor and plugins will be downloaded
     */
    'adminer_download_dir' => resource_path('adminer-admin'),

    /**
     * URL from which is Adminer Editor downloaded
     */
    'adminer_download_url' => 'https://github.com/vrana/adminer/releases/download/v4.3.1/editor-4.3.1-mysql.php',

    /**
     * configuration of plugins
     */
    'plugins_config' => [
        'field_image' => [
            'baseDir' => public_path('images'),
            'baseUrl' => asset('images'),
        ],
    ],
];