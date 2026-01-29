<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Handle penyimpanan Base64 ke Storage
     * Logic asli dari baris 944-962
     */
    public function saveSignature($ttdInput, $nip)
    {
        // Generate Nama File Unik (Logic asli baris 949)
        $filename = 'signatures/' . $nip . '_' . time() . '_' . Str::random(5) . '.png';

        // Resize & Encode (Logic asli baris 954-959)
        $image = Image::make($ttdInput)
            ->resize(300, null, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            })
            ->encode('png', 85);

        $disk = Storage::build([
            'driver' => 'local',
            'root'   => storage_path('app/private'), // Target Kunci
        ]);

        // Simpan file. Karena root sudah di 'app/private', 
        // maka $dbFilename 'signatures/...' akan masuk ke 'app/private/signatures/...'
        $disk->put($filename, $image);

        return $filename;
    }

    /**
     * Helper untuk convert Image Path ke Base64 (Untuk PDF)
     * Logic asli baris 1104-1114
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
        if ($data === false) return '';
        
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    
    /**
     * Helper proses TTD Petugas untuk PDF
     * Logic asli baris 1060-1074
     */
    public function processTtdForPdf($ttdPath)
    {
        if (empty($ttdPath)) {
            return null;
        }

        if (Str::contains($ttdPath, 'data:image')) {
            return $ttdPath; // Sudah base64
        }
        
        // Ubah path storage ke absolute path
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