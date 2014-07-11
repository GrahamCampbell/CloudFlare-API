<?php

/**
 * This file is part of Laravel CloudFlare API by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

return array(

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | CloudFlare Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Examples of
    | configuration have been included, but you may add as many connection as
    | you would like.
    |
    */

    'connections' => array(

        'main' => array(
            'token'   => 'your-token',
            'email'   => 'your-email',
            // 'baseurl' => 'https://www.cloudflare.com/api_json.html'
        ),

        'alternative' => array(
            'token'   => 'your-token',
            'email'   => 'your-email',
            // 'baseurl' => 'https://www.cloudflare.com/api_json.html'
        )

    )

);
