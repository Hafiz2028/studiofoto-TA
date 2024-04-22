<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Asset URL
    |--------------------------------------------------------------------------
    |
    | Use this option to specify the base URL for Livewire's assets.
    |
    */

    'asset_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire JavaScript Asset
    |--------------------------------------------------------------------------
    |
    | Here you may specify the JavaScript asset to be included in your layout file.
    |
    */

    'js_asset' => null,

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the temporary file upload settings for Livewire.
    |
    */

    'temporary_file_upload' => [
        'disk' => null,
        'rules' => null,
        'directory' => null,
        'visibility' => null,
        'preview_mimes' => ['image/jpeg', 'image/png'],
        'max_upload_time' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Manifest File Path
    |--------------------------------------------------------------------------
    |
    | Here you may specify the path to Livewire's manifest file.
    |
    */

    'manifest_path' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire View Caching
    |--------------------------------------------------------------------------
    |
    | Here you may configure the view caching settings for Livewire.
    |
    */

    'view_caching' => false,

    /*
    |--------------------------------------------------------------------------
    | Component Aliases
    |--------------------------------------------------------------------------
    |
    | This array contains the class aliases for Livewire components.
    |
    */

    'class_aliases' => [
        // 'alias' => \App\Http\Livewire\Component::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Directives
    |--------------------------------------------------------------------------
    |
    | This array contains the directives that will be registered with Blade.
    |
    */

    'directives' => [
        // 'directive' => \App\Http\Livewire\Directives\Directive::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Middleware Group
    |--------------------------------------------------------------------------
    |
    | This option defines the middleware group that will be applied to Livewire
    | routes. If this option is not set, the default web middleware group
    | will be applied.
    |
    */

    'middleware_group' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be applied to every Livewire route.
    |
    */

    'middleware' => [
        // 'web',
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Prefix
    |--------------------------------------------------------------------------
    |
    | This option defines the URL prefix that will be used for all Livewire
    | routes.
    |
    */

    'prefix' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire Namespace
    |--------------------------------------------------------------------------
    |
    | This option defines the namespace that will be used for all Livewire
    | components.
    |
    */

    'namespace' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire Layout
    |--------------------------------------------------------------------------
    |
    | This option defines the layout that will be used for all Livewire
    | components.
    |
    */

    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire Section
    |--------------------------------------------------------------------------
    |
    | This option defines the section that will be used for all Livewire
    | components.
    |
    */

    'section' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire Asset Versioning
    |--------------------------------------------------------------------------
    |
    | This option defines the versioning strategy for Livewire's assets.
    |
    */

    'asset_versioning' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire Component Aliases
    |--------------------------------------------------------------------------
    |
    | This array contains the class aliases for Livewire components.
    |
    */

    'component_aliases' => [
        // 'alias' => \App\Http\Livewire\Component::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Component View Caching
    |--------------------------------------------------------------------------
    |
    | This option defines the view caching strategy for Livewire components.
    |
    */

    'component_view_caching' => false,

    /*
    |--------------------------------------------------------------------------
    | Livewire Component View Caching Time
    |--------------------------------------------------------------------------
    |
    | This option defines the view caching time for Livewire components.
    |
    */

    'component_view_caching_time' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire Component View Caching Invalidation
    |--------------------------------------------------------------------------
    |
    | This option defines the view caching invalidation strategy for Livewire
    | components.
    |
    */

    'component_view_caching_invalidation' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire Component View Caching Invalidation Time
    |--------------------------------------------------------------------------
    |
    | This option defines the view caching invalidation time for Livewire
    | components.
    |
    */

    'component_view_caching_invalidation_time' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire Component View Caching Invalidation Strategy
    |--------------------------------------------------------------------------
    |
    | This option defines the view caching invalidation strategy for Livewire
    | components.
    |
    */

    'component_view_caching_invalidation_strategy' => null,

];
