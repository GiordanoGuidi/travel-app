<?php
// Consento le richieste da qualsiasi origine
header("Access-Control-Allow-Origin: *");
// Consento metodi specifici (GET, POST, ecc.)
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
// Consento specifici header nella richiesta
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Specifico che la risposta del server sarà in formato JSON
header('Content-Type: application/json');

//#Funzione per recuperare il viaggio
function getTripById($id)
{
    //Recupero il file json contenente tutti iviaggi
    $filePath = 'data/trips.json';
    if (!file_exists($filePath)) {
        return null;
    }
    //Recupero il contenuto del file
    $jsonContent = file_get_contents($filePath);
    //Trasformo il contenuto in un array associativo
    $trips = json_decode($jsonContent, true);
    //Itero nell'array
    foreach ($trips as $trip) {
        //Recupero il viaggio con lo stesso id
        if ($trip['id'] == $id) {
            return $trip;
        }
    }
    //Se non trovo il viaggio restituisco null
    return null;
}
//#Funzione per recuperare la tappa dal viaggio
function getStop($trip, $stop_day, $stop_id)
{
    //Recupero la giornata delle tappe
    $dayStops = $trip['days'][$stop_day];
    //itero nelle tappe della giornata
    foreach ($dayStops['stops'] as $stop) {
        //recupero la tappa con lo stesso id passato nella query
        if ($stop['id'] == $stop_id) {
            return $stop;
        }
    }
    return null;
}

//#Controllo che i dati nella richiesta siano presenti
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID mancante']);
    exit;
} else if (!isset($_GET['stop_day']) || $_GET['stop_day'] === '') {
    echo json_encode(['status' => 'error', 'message' => 'stop_day mancante']);
    exit;
} else if (!isset($_GET['stop_id']) || empty($_GET['stop_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'stop_id mancante']);
    exit;
}

//Controllo se l'id è passato come parametro nella richiesta HTTP
if (isset($_GET['id'])) {
    $tripId = $_GET['id'];
    //Invoco la funzione e assegno il valore alla variabile
    $trip = getTripById($tripId);
}
//Se il viaggio non viene trovato restituisco il messaggio
if ($trip === null) {
    echo json_encode(['status' => 'error', 'message' => 'Viaggio non trovato']);
    exit;
}

//Recupero il giorno delle tappe dalla richiesta HTTP
if (isset($_GET['stop_day'])) {
    $stop_day = $_GET['stop_day'];
}
if (isset($_GET['stop_id'])) {
    $stop_id = $_GET['stop_id'];
}
//Recupero la tappa
$stop = getStop($trip, $stop_day, $stop_id);

if ($stop !== null) {
    echo json_encode([
        'status' => 'success',
        'stop' => $stop,
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Tappa non trovata'
    ]);
}
