<?php
// Consento le richieste da qualsiasi origine
header("Access-Control-Allow-Origin: *");
// Consento metodi specifici (GET, POST, ecc.)
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
// Consento specifici header nella richiesta
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Specifico che la risposta del server sarà in formato JSON
header('Content-Type: application/json');

//#Funzione per recuperare il viaggio e aggiornare i file JSON
function updateTrips($id)
{
    $filePath = 'data/trips.json';
    //Risposta predefinita
    $response = ["status" => "error", "message" => "Viaggio non trovato."];
    if (!file_exists($filePath)) {
        echo json_encode(["status" => "error", "message" => "File trips.json non trovato."]);
        exit;
    }
    //Recupero il contenuto del file
    $jsonContent = file_get_contents($filePath);
    //Trasformo il contenuto in un array associativo
    $trips = json_decode($jsonContent, true);
    //Itero nell'array
    foreach ($trips as $tripIndex => $trip) {
        //Recupero il viaggio con lo stesso id
        if ($trip['id'] == $id) {
            //Invoco la funzione per eliminare il file del dettaglio del viaggio
            $detailResult = updateTripDetails($trip, $id);
            //Rimuovo il viaggio dall'array
            array_splice($trips, $tripIndex, 1);
            // Salvo i dati aggiornati nel file JSON
            file_put_contents($filePath, json_encode($trips));
            //Risposta per il frontend
            $response = [
                "status" => "success",
                "message" => "Viaggio eliminato dall'array di viaggi.",
                "trips" => $trips,
                "details" => $detailResult,
            ];
        }
    }
    echo json_encode($response);
}
//Funzione per aggiornare il file del dettaglio del viaggio
function updateTripDetails($trip, $id)
{
    //Recupero il file json dettaglio del viaggio
    $tripDetailsFile = 'data/trip_' . $trip['id'] . '.json';
    //recupero il contenuto del file
    $jsonContentDetailsTrip = file_get_contents($tripDetailsFile);
    //Trasformo il contenuto in un array associativo
    $tripDetails = json_decode($jsonContentDetailsTrip, true);
    // var_dump($tripDetails);
    if ($tripDetails['id'] === $id) {
        // elimino il file JSON che contiene il dettaglio del viaggio
        if (file_exists($tripDetailsFile)) {
            if (unlink($tripDetailsFile)) {
                // File eliminato correttamente
                return ['status' => 'success', 'message' => 'File del viaggio eliminato.'];
            } else {
                // Impossibile eliminare il file
                return ['status' => 'error', 'message' => 'Errore nell\'eliminazione del file del viaggio.'];
            }
        } else {
            // File non trovato
            return ['status' => 'error', 'message' => 'Il file del viaggio non esiste.'];
        }
    }
}

//Memorizzo nella variabile $input il corpo della richiesta HTTP
$input = file_get_contents('php://input');
//Decodifico da formato JSON in un array associativo PHP e assegno alla variabile $data
$data = json_decode($input, true);

//#Controllo che le chiavi esistano nell'array $data
if (!isset($data['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dati mancanti']);
    exit;
} else {
    $tripId = $data['id'];
    //Controllo il valore dell'id
    if (!is_string($tripId) || empty($tripId)) {
        echo json_encode(['status' => 'error', 'message' => 'ID non valido']);
        exit;
    }
    //Chiamo la funzione per aggiornare i viaggi
    updateTrips($tripId);
}
