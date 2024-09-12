<?php
// Consento le richieste da qualsiasi origine
header("Access-Control-Allow-Origin: *");
// Consento metodi specifici (GET, POST, ecc.)
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
// Consento specifici header nella richiesta
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Specifico che la risposta del server sarà in formato JSON
header('Content-Type: application/json');


//Funzione per recuperare il viaggio
function getTrips()
{
    //Recupero il file json contenente tutti iviaggi
    $filePath = 'data/trips.json';
    if (!file_exists($filePath)) {
        return null;
    } else {
        //Recupero il contenuto del file
        $jsonContent = file_get_contents($filePath);
        //Trasformo il contenuto in un array associativo
        $trips = json_decode($jsonContent, true);
        return $trips;
    }
    //Se non trovo i viaggi restituisco null
    return null;
}

$trips = getTrips();

if ($trips != null) {
    echo json_encode(['status' => 'success', 'trips' => $trips, 'message' => 'Response from InfinityFree backend']);
} else {
    // Restituisco un errore se il viaggio non è stato trovato
    echo json_encode([
        'status' => 'error',
        'message' => 'Nessun viaggio.'
    ]);
}
