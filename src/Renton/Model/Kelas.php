<?php
namespace Onlyongunz\Renton\Model;

class Kelas {

    public
        $id,
        $nama,
        $dosen,
        $kelas,
        $waktu,
        $hari,
        $ruang,
        $sks;

    const
        DATA_PATTERN = "/(<td([^\>]+)>)((.|\n)*?)(<\/td>)/i";

    public function __construct($object=null) {
        if($object!=null) {
            $buffer = array_intersect_key($object, array_flip([
                'id', 'nama', 'dosen', 'kelas', 'waktu', 'hari', 'ruang', 'sks'
            ]));
            foreach($buffer as $key=>$isi)
                $this->{$key}=$isi;
        }
    }

    public function parse($regex) {
        $regexed=[];
        preg_match_all(self::DATA_PATTERN, $regex, $regexed);
        $this->id=$regexed[3][1];
        $this->nama=html_entity_decode($regexed[3][2]);
        $this->sks=intval($regexed[3][3]);
        $this->kelas=$regexed[3][4];
        $this->dosen=html_entity_decode($regexed[3][5]);
        $this->hari=strip_tags($regexed[3][7]);
        //echo PHP_EOL . PHP_EOL . "DUMP INFO----" . PHP_EOL;
        //var_dump($regexed);
        //echo PHP_EOL . PHP_EOL . "DUMP INFO----" . PHP_EOL;
        
        $this->waktu=$regexed[3][8];
        $this->ruang=$regexed[3][9];
    }

    public static function parseRombongan($arrayOfIt) {
        $last=null;
        return array_map(function($data){
            global $last;
            $kelas = new Kelas();
            $kelas->parse($data);
            if($last!=null){
                if($kelas->nama==null)
                    $kelas->nama = $last->nama;
                if($kelas->id==null)
                    $kelas->id = $last->id;
            }
            $last=$kelas;
            return $kelas;
        }, $arrayOfIt);
    }
}