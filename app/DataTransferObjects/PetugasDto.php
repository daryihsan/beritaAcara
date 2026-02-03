<?php

namespace App\DataTransferObjects;

class PetugasDto
{
    public function __construct(
        public string $nip,
        public string $nama, 
        public string $pangkat,
        public string $jabatan,
        public mixed $ttd 
    ) {}
}