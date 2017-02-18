<?php
namespace Onlyongunz\Renton\Model;

class Identitas {

    public $nama,
        $npm,
        $email,
        $fakultas,
        $program_studi,
        $bidang_peminatan;
    
    protected $mapping = [
            "NPM"=>"npm",
            "Fakultas"=>"fakultas",
            "Nama"=>"nama",
            "Program Studi"=>"program_studi",
            "Email"=>"email",
            "Bidang Peminatan"=>"bidang_peminatan"
        ];

    const
        IDENTITY_PATTERN="/(\<td([\w\s\=\"\%]{0,})\>)((.|\n)*?)(<\/td>)/i";
    
    public function __cosntruct($object = null) {
    }

    public function parse($html) {
        $total_identitas = array_map(function ($data) {
            $temp = [];
            preg_match_all(self::IDENTITY_PATTERN, $data, $temp);
            return $temp[3];
        }, $html);
        foreach ($total_identitas as $d) {
            for ($i=0; $i<count($d); $i+=3) {
                if (array_key_exists(trim($d[$i]), $this->mapping)) {
                    $this->{$this->mapping[trim($d[$i])]}=$this->cleanup($d[$i+2]);
                }
            }
        }
    }

    private function cleanup($data) {
        $data = html_entity_decode(strip_tags($data));
        $data = preg_replace('/[^A-Za-z0-9\@\.]/', ' ', $data);
        $data = preg_replace('/ +/', ' ', $data);
        return trim($data);
    }
}
