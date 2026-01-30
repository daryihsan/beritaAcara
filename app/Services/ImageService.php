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
    public function imgBase64($file)
    {
        if (empty($file)) {
            return '';
        }

        $path = public_path('assets/img/' . $file);

        if (!is_file($path)) {
            return '';
        }

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = @file_get_contents($path);
        if ($data === false)
            return '';

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
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
            $type = pathinfo($fullPath, PATHINFO_EXTENSION);
            $imgData = @file_get_contents($fullPath);
            if ($imgData) {
                return 'data:image/' . $type . ';base64,' . base64_encode($imgData);
            }
        }

        return null;
    }
}