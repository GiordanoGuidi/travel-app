<?php
// Consento le richieste da qualsiasi origine
header("Access-Control-Allow-Origin: *");
// Consento metodi specifici (GET, POST, ecc.)
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
// Consento specifici header nella richiesta
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Specifico che la risposta del server sarà in formato JSON
header('Content-Type: application/json');

//#Funzione per recuperare il viaggio e aggiornare i dati nei file JSON
function updateTrip($tripId, $dayIndex, $stopId)
{
    //Recupero il file json contenente tutti i viaggi
    $filePath = 'data/trips.json';
    if (!file_exists($filePath)) {
        return null;
    }
    //Recupero il contenuto del file
    $jsonContent = file_get_contents($filePath);
    //Trasformo il contenuto in un array associativo
    $trips = json_decode($jsonContent, true);
    //Itero nell'array
    foreach ($trips as $index => $trip) {
        //Recupero il viaggio con lo stesso id
        if ($trip['id'] == $tripId) {
            foreach ($trip['days'][$dayIndex]['stops'] as $stopIndex => $stop) {
                if ($stop['id'] == $stopId) {
                    // Rimuovo la tappa dall'array
                    array_splice($trip['days'][$dayIndex]['stops'], $stopIndex, 1);
                    // Aggiorno l'array $trips con il viaggio aggiornato
                    $trips[$index] = $trip;
                    // Salvo i dati aggiornati nel file JSON
                    file_put_contents($filePath, json_encode($trips));
                    return $trip;
                }
            }
        }
    }
    //Se non trovo il viaggio restituisco null
    return null;
}
//Memorizzo nella variabile $input il corpo della richiesta HTTP
$input = file_get_contents('php://input');
//Decodifico da formato JSON in un array associativo PHP e assegno alla variabile $data
$data = json_decode($input, true);

//#Controllo che le chiavi esistano nell'array $data
if (!isset($data['tripId']) || !isset($data['stopId']) || !isset($data['dayIndex'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dati mancanti']);
    exit;
} else {
    $tripId = $data['tripId'];
    $stopId = $data['stopId'];
    $dayIndex = $data['dayIndex'];
    //Recupero il viaggio e rimuovo la tappa dal file di tutti i viaggi
    $trip = updateTrip($tripId, $dayIndex, $stopId);
    if ($trip) {
        //Recupero il file json dettaglio del viaggio
        $tripDetailsFile = 'data/trip_' . $trip['id'] . '.json';
        //recupero il contenuto ddel file
        $jsonContentDetailsTrip = file_get_contents($tripDetailsFile);
        //Trasformo il contenuto in un array associativo
        $tripDetails = json_decode($jsonContentDetailsTrip, true);
        foreach ($tripDetails['days'][$dayIndex]['stops'] as $stopIndex => $stop) {
            if ($stop['id'] == $stopId) {
                // Rimuovo la tappa dall'array
                array_splice($tripDetails['days'][$dayIndex]['stops'], $stopIndex, 1);
            }
            // Salvo il file JSON aggiornato
            file_put_contents($tripDetailsFile, json_encode($tripDetails));
        }
        echo json_encode([
            'status' => 'success',
            'trip' => $trip
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Viaggio o tappa non trovati'
        ]);
    }
}
