@if($log->properties && $log->properties->has('attributes'))
    <div class="max-h-32 overflow-y-auto custom-scrollbar">
        @foreach($log->properties['attributes'] as $key => $val)
            @if(!in_array($key, ['updated_at', 'created_at']))
                <div class="text-xl mb-1 border-b border-slate-100 pb-1">
                    <span class="font-bold text-blue-600">
                        {{ ucwords(str_replace('_', ' ', $key)) }}:
                    </span>
                    <span class="break-all">
                        {{Str::limit((string) $val, 150)}}
                    </span>
                </div>
            @endif
        @endforeach
    </div>
@else
    <span class="text-slate-400">- Tidak ada detail -</span>
@endif