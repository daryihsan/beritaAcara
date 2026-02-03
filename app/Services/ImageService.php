<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Handle penyimpanan Base64 ke Storage (private)
     */
    public function saveSignature($ttdInput, $nip)
    {
        // Generate nama file unik 
        $filename = 'signatures/' . $nip . '_' . time() . '_' . Str::random(5) . '.png';

        // Resize & encode
        $image = Image::make($ttdInput)
            ->resize(300, null, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            })
            ->encode('png', 85);

        $disk = Storage::build([
            'driver' => 'local',
            'root' => storage_path('app/private'), // Target Kunci
        ]);

        // Simpan file
        $disk->put($filename, $image);

        return $filename;
    }

    /**
     * Helper untuk convert Image Path ke Base64 (Untuk PDF)
     */
    public function imgPath($file)
    {
        $path = public_path('assets/img/' . $file);
         if (file_exists($path)) {
             return 'file://' . $path;
         }
         return '';
    }

    /**
     * Helper proses TTD Petugas untuk PDF
     */
    public function processTtdForPdf($ttdPath)
    {
        if (empty($ttdPath)) {
            return null;
        }

        if (Str::contains($ttdPath, 'data:image')) {
            return $ttdPath; // Sudah base64
        }

        // Absolute path
        $fullPath = storage_path('app/private/' . $ttdPath);

        if (file_exists($fullPath) && is_file($fullPath)) {
            // Return path untuk PDF
            // Format: "file:///C:/path/to/project/storage/app/private/signatures/file.png"
            return 'file://' . $fullPath; 
        }

        return null;
    }
}