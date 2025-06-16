<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Branch Deploy
    |--------------------------------------------------------------------------
    |
    | When you do the push or merge request to this branch, the auto deploy will work
    */

    'branch_deploy' => env('BRANCH_DEPLOY', 'main'),

    /*
    |--------------------------------------------------------------------------
    | Using Submodule
    |--------------------------------------------------------------------------
    |
    | Set to 1 if you you used submodule otherwise leave as default to 0
    */

    'using_submodule' => env('USING_SUBMODULE', 0),

    /*
    |--------------------------------------------------------------------------
    | Hooks
    |--------------------------------------------------------------------------
    |
    | You can register any commands here that will be executed pre deployment and post deployment.
    | The commands is grouped by environment.
    */

    'hooks' => [

        'pre' => [

            'local' => [],

            'production' => [],

        ],

        'post' => [

            'local' => [],

            'production' => [
                //                uncomment these lines to optimize laravel project on production environment
                //                these commands aren't available on lumen project,
                //                so feel free to remove these lines if your project is using lumen
                //                see https://laravel.com/docs/master/deployment#optimization
                //'php artisan config:cache',
                //'php artisan route:cache',
                //'php artisan event:cache',
                //'php artisan view:cache',
            ],

        ],

    ],

];
