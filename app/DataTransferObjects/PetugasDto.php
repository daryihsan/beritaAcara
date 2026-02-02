<?php

namespace App\DataTransferObjects;

class PetugasDto
{
    public function __construct(
        public string $nip,
        public string $nama, // Opsional, kadang cuma butuh NIP
        public string $pangkat,
        public string $jabatan,
        public mixed $ttd // Bisa string base64, path, atau null
    ) {}
}