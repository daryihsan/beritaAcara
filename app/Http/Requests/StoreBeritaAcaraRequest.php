<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBeritaAcaraRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'no_surat_tugas'    => 'required|string',
            'tgl_surat_tugas'   => 'required|date',
            'tanggal'           => 'required|date',
            'hari'              => 'required|string',
            'objek_nama'        => 'required|string',
            'objek_alamat'      => 'required|string',
            'hasil_pemeriksaan' => 'required|string',
            'petugas_nip'       => 'required|array|min:1',
            'petugas_nip.*'     => 'required|string|exists:users,nip',
            'kepala_balai_text' => 'required|string',
            'objek_kota'        => 'required|string',
            'dalam_rangka'      => 'required|string',
            'yang_diperiksa'    => 'required|string',
            'petugas_nama'      => 'required|array',
            'petugas_pangkat'   => 'nullable|array',
            'petugas_jabatan'   => 'nullable|array',
            'ttd'               => 'image|mimes:png,jpg|max:300',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'petugas_nip.required' => 'Data petugas belum ditambahkan.',
            'petugas_nip.min'      => 'Harus ada minimal 1 petugas dalam Berita Acara.',
            'petugas_nip.*.exists' => 'Salah satu NIP petugas tidak valid atau tidak terdaftar.',
        ];
    }
}
