<?php
// Consento le richieste da qualsiasi origine
header("Access-Control-Allow-Origin: http://localhost:5173");
// Consento metodi specifici (GET, POST, ecc.)
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
// Consento specifici header nella richiesta
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Specifico che la risposta del server sarà in formato JSON
header('Content-Type: application/json');

//#Funzione per recuperare il viaggio e aggiornare i dati nei file JSON
function updateTripAndDetails($tripId, $dayIndex, $stopId, $status)
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
    // Variabile per salvare il viaggio aggiornato 
    $updatedTrip = null;
    //Itero nell'array
    foreach ($trips as $index => $trip) {
        //Recupero il viaggio con lo stesso id
        if ($trip['id'] == $tripId) {
            foreach ($trip['days'][$dayIndex]['stops'] as $stopIndex => $stop) {
                if ($stop['id'] == $stopId) {
                    // Aggiorno lo status della tappa direttamente nell'array principale
                    $trips[$index]['days'][$dayIndex]['stops'][$stopIndex]['status'] = $status;
                    //Assegno alla variabile il valore del viaggio
                    $updatedTrip = $trips[$index];
                }
            }
            // Salvo i dati aggiornati nel file JSON
            file_put_contents($filePath, json_encode($trips));
            break;
        }
    }
    //Aggiorno il file del dettaglio del viaggio
    if ($updatedTrip) {
        // Recupero il file json dettaglio del viaggio
        $tripDetailsFile = 'data/trip_' . $updatedTrip['id'] . '.json';
        if (file_exists($tripDetailsFile)) {
            // Recupero il contenuto del file
            $jsonContentDetailsTrip = file_get_contents($tripDetailsFile);
            // Trasformo il contenuto in un array associativo
            $tripDetails = json_decode($jsonContentDetailsTrip, true);
            foreach ($trip['days'][$dayIndex]['stops'] as $stopIndex => $stop) {
                if ($stop['id'] == $stopId) {
                    // Aggiorno lo status della tappa direttamente nell'array principale
                    $tripDetails['days'][$dayIndex]['stops'][$stopIndex]['status'] = $status;
                }
            }
            // Salvo i dati aggiornati nel file JSON
            file_put_contents($tripDetailsFile, json_encode($tripDetails));
        }
        return $updatedTrip;
    }
    //Se non trovo il viaggio restituisco null
    return null;
}

//Memorizzo nella variabile $input il corpo della richiesta HTTP
$input = file_get_contents('php://input');
//Decodifico da formato JSON in un array associativo PHP e assegno alla variabile $data
$data = json_decode($input, true);

//#Controllo che le chiavi esistano nell'array $data
if (!isset($data['tripId']) || !isset($data['stopId']) || !isset($data['dayIndex']) || !isset($data['status'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dati mancanti']);
    exit;
} else {
    $tripId = $data['tripId'];
    $stopId = $data['stopId'];
    $dayIndex = $data['dayIndex'];
    $status = $data['status'];

    $trip = updateTripAndDetails($tripId, $dayIndex, $stopId, $status);
    if ($trip) {
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
