<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PrivateFileController extends Controller
{
    /**
     * Serve Signature Image Securely
     */
    public function getSignature($filename)
    {
        // Pastikan User login (double check selain middleware)
        if (!Auth::check()) {
            abort(403, 'Unauthorized action.');
        }

        // Cek file di disk local (private)
        $path = storage_path('app/private/signatures/' . $filename);

        if (!file_exists($path)) {
            abort(404); // File tidak ada
        }

        // Return file
        return response()->file($path);
    }
}