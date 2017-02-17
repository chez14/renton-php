<?php
include '../vendor/autoload.php';
use Onlyongunz\Renton\Renton;

$jas = new Renton();
$jas->parse_html("../target/jadwal.html");

header("Content-type: application/json");
echo $jas->toJSON(null, JSON_PRETTY_PRINT);