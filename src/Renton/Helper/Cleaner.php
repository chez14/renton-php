<?php
namespace Onlyongunz\Renton\Helper;

class Cleaner {
    public static function cleanup($data) {
        $data = html_entity_decode(strip_tags($data));
        $data = preg_replace('/[^A-Za-z0-9\@\.\:]/', ' ', $data);
        $data = preg_replace('/ +/', ' ', $data);
        return trim($data);
    }
}
