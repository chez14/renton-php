<?php
namespace Onlyongunz\Renton\Model;

use Onlyongunz\Renton\Helper\Cleaner;


/**
 * Kelas Identitas merepresentasikan pemilik dari jadwal ini.
 * anda dapat mengekstrak email, nama, fakultas, bahkan npm untuk jadwal
 * ini.
 *
 * @author     Onlyongunz <gunawan.mr.blue@gmail.com>
 */
class Identitas {

    const
        IDENTITY_PATTERN="/(\<td([\w\s\=\"\%]{0,})\>)((.|\n)*?)(<\/td>)/i";

    protected $mapping = [
            "NPM"=>"npm",
            "Fakultas"=>"fakultas",
            "Nama"=>"nama",
            "Program Studi"=>"program_studi",
            "Email"=>"email",
            "Bidang Peminatan"=>"bidang_peminatan"
        ];
    
    public $nama,
        $npm,
        $email,
        $fakultas,
        $program_studi,
        $bidang_peminatan;
    
    /**
     * @param $object massukan objek yang sudah pernah ada sebelumnya
     */
    public function __cosntruct($object = null) {
        if($object!=null) {
            $buffer = array_intersect_key($object, array_flip([
                'npm', 'nama', 'email', 'bidang_peminatan', 'fakultas', 'program_studi'
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
        $total_identitas = array_map(function ($data) {
            $temp = [];
            preg_match_all(self::IDENTITY_PATTERN, $data, $temp);
            return $temp[3];
        }, $html);
        foreach ($total_identitas as $d) {
            for ($i=0; $i<count($d); $i+=3) {
                if (array_key_exists(trim($d[$i]), $this->mapping)) {
                    $this->{$this->mapping[trim($d[$i])]}=Cleaner::cleanup($d[$i+2]);
                }
            }
        }
    }
}
