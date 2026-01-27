@php
    $colors = [
        'created' => 'bg-green-100 text-green-700 border-green-200',
        'updated' => 'bg-blue-100 text-blue-700 border-blue-200',
        'deleted' => 'bg-red-100 text-red-700 border-red-200',
    ];
    
    // Default color (gray) jika event tidak dikenal
    $defaultColor = 'bg-gray-100 text-gray-700 border-gray-200';
    $badgeColor = $colors[$log->event] ?? $defaultColor;
    
    // Mapping teks bahasa Indonesia
    $labels = [
        'created' => 'TAMBAH',
        'updated' => 'UPDATE',
        'deleted' => 'HAPUS',
    ];
    $labelText = $labels[$log->event] ?? strtoupper($log->event);
@endphp

<span class="px-3 py-1 rounded-full text-lg font-bold border {{ $badgeColor }}">
    {{ $labelText }}
</span>