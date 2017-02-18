<?php
namespace Onlyongunz\Renton\Model;

use Onlyongunz\Renton\Helper\Cleaner;

class JadwalMeta {

    public $kode_jadwal,
        $jadwal_tahun = "2016-2017",
        $semester = "GENAP",
        $update;
    
    const
        PATTERNS = [
            "kode_jadwal"=>"/(<span id=\"codeBTI\">)((.|\n)*?)(<\/span>)/i",
            "smester_tahun"=>"/(GANJIL|GENAP|PENDEK) \- ([0-9]{4}) \/ ([0-9]{4})/",
            "update"=>"/(Cetak \: (([0-9]{1,2}) ([a-zA-Z]{3,4}) ([0-9]{4}) ([0-9]{2}\:[0-9]{2})))/i"
        ];

    public function parse($html) {
        //get kode_jadwal.
        $jadwals = [];
        preg_match_all(self::PATTERNS['kode_jadwal'], $html, $jadwals);
        $this->kode_jadwal=Cleaner::cleanup($jadwals[2][0]);

        //get update
        $jadwals = [];
        preg_match_all(self::PATTERNS['update'], $html, $jadwals);
        $this->update = Cleaner::cleanup($jadwals[2][0]);

        //get semster and tahun jadwal
        $jadwals = [];
        preg_match_all(self::PATTERNS['smester_tahun'], $html, $jadwals);
        $this->jadwal_tahun = sprintf("%d-%d", $jadwals[2][0], $jadwals[3][0]);
        $this->semester = Cleaner::cleanup($jadwals[1][0]);
    }
}
