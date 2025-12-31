<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    /**
     * Get the public URL for a given storage path, with fallback disk checks.
     * 
     * @param string|null $path
     * @return string|null
     */
    public static function getUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        // If it's already a full URL, return it as is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // 1. Support for explicit disk prefixing: "disk_name::path/to/file.jpg"
        if (str_contains($path, '::')) {
            [$disk, $filePath] = explode('::', $path, 2);
            try {
                /** @var \Illuminate\Filesystem\FilesystemAdapter $storageDisk */
                $storageDisk = Storage::disk($disk);
                return $storageDisk->url($filePath);
            } catch (\Exception $e) {
                // Fall through if disk doesn't exist
            }
        }

        // 2. Try the current default disk
        $defaultDisk = config('filesystems.default');
        try {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $storageDisk */
            $storageDisk = Storage::disk($defaultDisk);
            if ($storageDisk->exists($path)) {
                return $storageDisk->url($path);
            }
        } catch (\Exception $e) {
            // Fall through
        }

        // 3. Fallback search on known disks in priority order
        $fallbackDisks = ['s3', 'public', 'local'];

        // Remove default disk from fallback to avoid double checking
        $fallbackDisks = array_diff($fallbackDisks, [$defaultDisk]);

        foreach ($fallbackDisks as $disk) {
            try {
                /** @var \Illuminate\Filesystem\FilesystemAdapter $storageDisk */
                $storageDisk = Storage::disk($disk);
                if ($storageDisk->exists($path)) {
                    return $storageDisk->url($path);
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Final fallback: just return the URL from the currently configured default disk
        return Storage::url($path);
    }
}
