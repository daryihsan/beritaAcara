<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

class BapDto
{
    /**
     * @param PetugasDto[] $listPetugas  <-- Kita kasih type hint array of objects
     */
    public function __construct(
        public string $noSuratTugas,
        public string $tglSuratTugas,
        public string $tanggalPemeriksaan,
        public string $hari,
        public string $objekNama,
        public string $objekAlamat,
        public ?string $objekKota,
        public ?string $dalamRangka,
        public string $hasilPemeriksaan,
        public ?string $yangDiperiksa,
        public ?string $kepalaBalaiText,
        public ?string $createdBy,
        public array $listPetugas = [] 
    ) {}

    public static function fromRequest(Request $request): self
    {
        // Mapping petugas (Parallel Array ke Object List) 
        $listPetugasObj = [];
        $nips = $request->petugas_nip ?? []; // Array dari form
        
        foreach ($nips as $index => $nip) {
            if (empty($nip)) continue;

            $listPetugasObj[] = new PetugasDto(
                nip: $nip,
                nama: $request->petugas_nama[$index] ?? null,
                pangkat: $request->petugas_pangkat[$index] ?? null,
                jabatan: $request->petugas_jabatan[$index] ?? null,
                ttd: $request->petugas_ttd[$index] ?? null
            );
        }

        return new self(
            noSuratTugas: $request->no_surat_tugas,
            tglSuratTugas: $request->tgl_surat_tugas,
            tanggalPemeriksaan: $request->tanggal, 
            hari: $request->hari,
            objekNama: $request->objek_nama,
            objekAlamat: $request->objek_alamat,
            objekKota: $request->objek_kota,
            dalamRangka: $request->dalam_rangka,
            hasilPemeriksaan: $request->hasil_pemeriksaan,
            yangDiperiksa: $request->yang_diperiksa,
            kepalaBalaiText: $request->kepala_balai_text,
            createdBy: auth()->id(),
            listPetugas: $listPetugasObj 
        );
    }

    public function toArray(): array
    {
        return [
            'no_surat_tugas' => $this->noSuratTugas,
            'tgl_surat_tugas' => $this->tglSuratTugas,
            'tanggal_pemeriksaan' => $this->tanggalPemeriksaan,
            'hari' => $this->hari,
            'objek_nama' => $this->objekNama,
            'objek_alamat' => $this->objekAlamat,
            'objek_kota' => $this->objekKota,
            'dalam_rangka' => $this->dalamRangka,
            'hasil_pemeriksaan' => $this->hasilPemeriksaan,
            'yang_diperiksa' => $this->yangDiperiksa,
            'kepala_balai_text' => $this->kepalaBalaiText,
            'created_by' => $this->createdBy,
        ];
    }
}