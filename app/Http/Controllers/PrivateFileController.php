<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PrivateFileController extends Controller
{
    /**
     * Serve Signature Image Securely
     */
    public function getSignature($filename)
    {
        // 1. Pastikan User Login (Double check selain middleware)
        if (!Auth::check()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Cek File di Disk Local (Private)
        $path = storage_path('app/private/signatures/' . $filename);

        // dd($path);

        if (!file_exists($path)) {
            abort(404); // File tidak ketemu
        }

        // 3. Return File
        return response()->file($path);
    }
}