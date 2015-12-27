<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Driver
    |--------------------------------------------------------------------------
    |
    | Laravel supports both SMTP and PHP's "mail" function as drivers for the
    | sending of e-mail. You may specify which one you're using throughout
    | your application here. By default, Laravel is setup for SMTP mail.
    |
    | Supported: "smtp", "mail", "sendmail", "mailgun", "mandrill", "ses", "log"
    |
    */

    'driver' => env('MAIL_DRIVER', 'smtp'),
    'host' => env('MAIL_HOST', 'mailtrap.io'),
    'port' => env('MAIL_PORT', 2525),
    'from' => ['address' => env('MAIL_FROM', 'ankhzet@gmail.com'), 'name' => env('MAIL_SENDER', 'Ankh Zet')],
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME', '437748c0733d98558'),
    'password' => env('MAIL_PASSWORD', '757edfee13ca36'),
    'sendmail' => '/usr/sbin/sendmail -bs',
    'pretend' => false,

];
