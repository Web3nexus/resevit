<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads
    |--------------------------------------------------------------------------
    |
    | Livewire handles file uploads by storing them in a temporary directory
    | before they are stored permanently. Here you may configure the
    | directory, disk, and validation rules for those uploads.
    |
    */

    'temporary_file_upload' => [
        'disk' => 'livewire',
        'rules' => 'file|max:12288', // 12MB max
        'directory' => null,
        'middleware' => null,
        'preview_mimes' => [
            'png',
            'gif',
            'bmp',
            'svg',
            'wav',
            'mp4',
            'mov',
            'avi',
            'wmv',
            'mp3',
            'm4a',
            'jpg',
            'jpeg',
            'mpga',
            'webp',
            'pdf',
        ],
    ],

];
