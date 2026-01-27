@if($log->causer)
    <div class="font-bold">{{ $log->causer->name }}</div>
    <div class="text-xs text-slate-400">{{ $log->causer->nip }}</div>
@else
    <span class="text-red-400 italic">Sistem/Terhapus</span>
@endif