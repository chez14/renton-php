<?php
namespace Onlyongunz\Renton;
/**
 * Proyek Renton; Translasi semua jadwal dari Student Portal menjadi file JSON.
 * 
 * Proyek Renton adalah sebuah proyek kecil yang saya kerjakan untuk membantu saya
 * membuat visualisasi dari jadwal. Semenjak saya masih baru masuk, maka saya terlalu
 * malas untuk membuat visualisasi tiap kali jadwal berganti setelah PRS, atau semester
 * baru.
 * Salah satu goal project ini untuk membantu para *geek* memproses datanya, dengan
 * mengkonversikan sejumlah data menjadi file JSON.
 * Setelah itu mereka dapat menggunakan file JSON tersebut dengan mudah sesuai passion
 * mereka.
 * 
 * PHP versions 5 and 7
 * Biar banyak yang dukung :P
 *
 * @package    Onlyongunz\Renton
 * @author     Onlyongunz <gunawan.mr.blue@gmail.com>
 * @link       https://github.com/onlyongunz/Renton-php
 */

/**
 * Kelas utama Project Renton; Sumber siklus file. Kita hanya akan memerlukan
 * file html dari halaman cetak Student Portal anda.
 * @package Onlyongunz\Renton
 */
class Renton {

    private
        $html_location="",
        $jadwal_list=[];

    const
        JADWAL_PATTERN = [
            "jadwal"=>[
                "pattern"=>"/(<tr class\=\"(row_even|row_odd)\">)((.|\n)*?)(<\/tr>)/i",
                "offset"=>0
                ],
            "identitas"=>[
                "pattern"=>"(<tr>)((.|\n)*?)(<\/tr>)",
                "offset"=>2
                ]
        ];

    public function parse_html($htmlfile){
        $this->html_location=$htmlfile;
        $this->intepret_jadwal();
    }

    public function toJSON($json_path=null, $json_options=null){
        if($json_path==null)
            return json_encode($this->jadwal_list, $json_options);

        return file_put_contents($json_path, json_encode($this->jadwal_list, $json_options));
    }

    public function get_list_jadwal(){
        return $this->jadwal_list;
    }
/*
    public function add_jadwal(){

    }

    public function remove_jadwal($mkid){

    }
*/
    private function intepret_jadwal(){
        $html=file_get_contents($this->html_location);
        $jadwals=[];
        preg_match_all(self::JADWAL_PATTERN['jadwal']['pattern'], $html, $jadwals);
        $ruby = Model\Kelas::parseRombongan($jadwals[3]);
        $this->jadwal_list = $ruby;
    }
}