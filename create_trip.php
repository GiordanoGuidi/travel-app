<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dataFile = 'data/trips.json';
    //Leggo i dati esistenti
    $trips = [];
    if (file_exists($dataFile)) {
        $trips = json_decode(file_get_contents($dataFile), true);
    }
}
