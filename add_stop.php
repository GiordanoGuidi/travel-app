<?php
// Consento le richieste da qualsiasi origine
header("Access-Control-Allow-Origin: http://localhost:5173");
// Consento metodi specifici (GET, POST, ecc.)
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
// Consento specifici header nella richiesta
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Specifico che la risposta del server sarà in formato JSON
header('Content-Type: application/json');

//#Funzione per recuperare il viaggio
function updateTrip($tripId, $stopDay, $data)
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
            //Aggiungo i dati della tappa nell'array delle giornate all'indice corretto
            array_push($trip['days'][$stopDay]['stops'], $data);
            // Sostituisco il vecchio viaggio con la versione aggiornata
            $trips[$index] = $trip;
            // Salvo i dati aggiornati nel file JSON
            file_put_contents($filePath, json_encode($trips));
            return $trip;
        }
    }
    //Se non trovo il viaggio restituisco null
    return null;
}
//Memorizzo nella variabile $input il corpo della richiesta HTTP
$input = file_get_contents('php://input');
//Decodifico da formato JSON in un array associativo PHP e assegno alla variabile $data
$data = json_decode($input, true);
//Genereo un id univoco
$id = uniqid();
//Aggiungo la chiave id all'oggetto tappa
$data['id'] = $id;

//Controllo se day è passato come parametro nella richiesta HTTP
if (isset($_GET['day'])) {
    $stopDay = $_GET['day'];
}

//Controllo se l'id è passato come parametro nella richiesta HTTP
if (isset($_GET['id'])) {
    $tripId = $_GET['id'];
    //Invoco la funzione e assegno il valore alla variabile
    $trip = updateTrip($tripId, $stopDay, $data);
    //Recupero il file json dettaglio del viaggio
    $tripDetailsFile = 'data/trip_' . $trip['id'] . '.json';
    //recupero il contenuto ddel file
    $jsonContentDetailsTrip = file_get_contents($tripDetailsFile);
    //Trasformo il contenuto in un array associativo
    $tripDetails = json_decode($jsonContentDetailsTrip, true);
    //Aggiungo i dati della tappa nell'array delle giornate all'indice corretto
    array_push($tripDetails['days'][$stopDay]['stops'], $data);
    // Salvo i dati aggiornati nel file JSON
    file_put_contents($tripDetailsFile, json_encode($tripDetails));
    if ($trip !== null) {
        echo json_encode([
            'status' => 'success',
            'trip' => $trip
        ]);
    } else {
        // Restituisco un errore se il viaggio non è stato trovato
        echo json_encode([
            'status' => 'error',
            'message' => 'Viaggio non trovato.'
        ]);
    }
} else {
    // Restituisco un errore se l'ID non è stato fornito
    echo json_encode([
        'status' => 'error',
        'message' => 'ID non fornito.'
    ]);
}
