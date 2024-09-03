<?php
// Consento le richieste dal dominio del frontend
header("Access-Control-Allow-Origin: *");
// Consento metodi specifici (GET, POST, ecc.)
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
// Consento specifici header nella richiesta
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Specifico che la risposta del server sarà in formato JSON
header('Content-Type: application/json');

//Funzione per recuperare il viaggio
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
//#Controllo che i dati nella richiesta siano presenti
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID mancante']);
    exit;
}

//Controllo se l'id è passato come parametro nella richiesta HTTP
if (isset($_GET['id'])) {
    $tripId = $_GET['id'];
    //Invoco la funzione e assegno il valore alla variabile
    $trip = getTripById($tripId);

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
}
