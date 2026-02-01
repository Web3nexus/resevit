<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenantStorageController extends Controller
{
    /**
     * Serve a file from the current tenant's public storage.
     */
    public function show(Request $request, string $path): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        // Decode the path to handle special characters
        $path = urldecode($path);

        // Use the tenant-aware 'public' disk
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        // Check if file exists
        if (! $disk->exists($path)) {
            abort(404, 'File not found');
        }

        // Stream the file with appropriate headers
        return response()->file($disk->path($path));
    }
}
