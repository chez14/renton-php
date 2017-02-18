<?php
namespace Onlyongunz\Renton\Model;

/**
 * Kelas Kelas merepresentasikan jadwal pada kelas.
 * Kelas ini akan memparsing data dari html dan menyimpannya
 * apda atributnya masing masing.
 *
 * @author     Onlyongunz <gunawan.mr.blue@gmail.com>
 */
class Kelas {

    const
        DATA_PATTERN = "/(<td([^\>]+)>)((.|\n)*?)(<\/td>)/i";

    public
        $id,
        $nama,
        $dosen,
        $kelas,
        $waktu,
        $hari,
        $ruang,
        $sks;

    /**
     * @param $object massukan objek yang sudah pernah ada sebelumnya
     */
    public function __construct($object=null) {
        if($object!=null) {
            $buffer = array_intersect_key($object, array_flip([
                'id', 'nama', 'dosen', 'kelas', 'waktu', 'hari', 'ruang', 'sks'
            ]));
            foreach($buffer as $key=>$isi)
                $this->{$key}=$isi;
        }
    }

    /**
     * Parsing langsung dari halaman student portal.
     * Mohon di catat, karena ini ternyata pattern bekerja juga pada
     * pendeteksi jadwal UTS/UAS, maka mohon pastikan yang masuk hanya jadwal kelas
     * saja
     *
     * @param $html full isi file html yag akan di parsing
     */
    public function parse($regex) {
        $regexed=[];
        preg_match_all(self::DATA_PATTERN, $regex, $regexed);
        $this->id=$regexed[3][1];
        $this->nama=html_entity_decode($regexed[3][2]);
        $this->sks=intval($regexed[3][3]);
        $this->kelas=$regexed[3][4];
        $this->dosen=html_entity_decode($regexed[3][5]);
        $this->hari=strip_tags($regexed[3][7]);
        $this->waktu=$regexed[3][8];
        $this->ruang=$regexed[3][9];
    }

    /**
     * Parsing langsung dari halaman student portal,
     * tapi yang ini dapat menghandle banyak jadwal langsung.
     * 
     * @param $arrayOfIt array dari tabel tablenya
     * @return array dari objek kelas ini yang sudah di parsing.
     */
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