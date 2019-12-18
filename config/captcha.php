<?php

return [
    'sitekey'	=> env('NOCAPTCHA_SITEKEY'),
    'secret'	=> env('NOCAPTCHA_SECRET'),
    'options'	=> [
        'timeout'	=> 30,
    ],
];
