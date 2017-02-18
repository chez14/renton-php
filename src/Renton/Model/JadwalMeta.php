<?php
namespace Onlyongunz\Renton\Model;

use Onlyongunz\Renton\Helper\Cleaner;

/**
 * Kelas ini menyimpan informasi tentang si jadwal,
 * tanggal update, jadwal semester genap/ganjil, dan kode jadwal tersebut.
 *
 * @author     Onlyongunz <gunawan.mr.blue@gmail.com>
 */
class JadwalMeta {
    
    const
        PATTERNS = [
            "kode_jadwal"=>"/(<span id=\"codeBTI\">)((.|\n)*?)(<\/span>)/i",
            "smester_tahun"=>"/(GANJIL|GENAP|PENDEK) \- ([0-9]{4}) \/ ([0-9]{4})/",
            "update"=>"/(Cetak \: (([0-9]{1,2}) ([a-zA-Z]{3,4}) ([0-9]{4}) ([0-9]{2}\:[0-9]{2})))/i"
        ];

    public $kode_jadwal,
        $jadwal_tahun = "2016-2017",
        $semester = "GENAP",
        $update;

    /**
     * @param $object massukan objek yang sudah pernah ada sebelumnya
     */
    public function __construct($object=null) {
        if($object!=null) {
            $buffer = array_intersect_key($object, array_flip([
                'jadwal_tahun', 'semester', 'update'
            ]));
            foreach($buffer as $key=>$isi)
                $this->{$key}=$isi;
        }
    }

    /**
     * Parsing langsung dari halaman student portal.
     * 
     * @param $html full isi file html yag akan di parsing
     */
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
