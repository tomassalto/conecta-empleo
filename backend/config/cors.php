<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:4321'],
    'allowed_headers' => ['*'],
    'supports_credentials' => true,
];
