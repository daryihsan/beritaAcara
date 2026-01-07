public function assign(Request $request)
{
    $request->validate([
        'berita_acara_id' => 'required',
        'user_ids' => 'required|array'
    ]);

    $ba = BeritaAcara::findOrFail($request->berita_acara_id);

    $ba->petugas()->syncWithoutDetaching($request->user_ids);

    return back()->with('success', 'Petugas berhasil ditambahkan');
}
