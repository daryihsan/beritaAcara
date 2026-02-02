<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class BapException extends Exception
{
    public function report()
    {
        Log::error("BAP Error: " . $this->getMessage());
    }

    // Render exception ke HTTP response
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
                'status' => 'error'
            ], 500);
        }

        return back()->withErrors(['system_error' => $this->getMessage()])->withInput();
    }
}