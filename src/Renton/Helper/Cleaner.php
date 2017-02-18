<?php
namespace Onlyongunz\Renton\Helper;

/**
 * Kelas pembantu untuk membersihkan beberapa string.
 *
 * @author     Onlyongunz <gunawan.mr.blue@gmail.com> 
 */
class Cleaner {
    /**
     * Pembersih sisa-sisa html yang membandel.
     * kami akan menghapus tag html, special caracter, dan multiple space
     * 
     * @param $data string yang akan dibersihkan
     * @return String biasa yang sudah bersih difilter
     */
    public static function cleanup($data) {
        $data = html_entity_decode(strip_tags($data));
        $data = preg_replace('/[^A-Za-z0-9\@\.\:]/', ' ', $data);
        $data = preg_replace('/ +/', ' ', $data);
        return trim($data);
    }
}
