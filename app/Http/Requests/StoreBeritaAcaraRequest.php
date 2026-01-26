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
            'no_surat_tugas'    => 'required',
            'tgl_surat_tugas'   => 'required|date',
            'tanggal'           => 'required|date',
            'hari'              => 'required',
            'objek_nama'        => 'required',
            'objek_alamat'      => 'required',
            'hasil_pemeriksaan' => 'required',
            'petugas_nip'       => 'required|array',
            'petugas_nip.*'     => 'exists:users,nip',
            'kepala_balai_text' => 'required',
            'objek_kota'        => 'required',
            'dalam_rangka'      => 'required',
            'yang_diperiksa'    => 'required',
            'ttd'               => 'image|mimes:png,jpg|max:300',
        ];
    }

    public function messages()
    {
        return [
            'petugas_nip.*.exists' => 'NIP Petugas tidak terdaftar di sistem!',
            'required' => ':attribute wajib diisi.',
        ];
    }
}
