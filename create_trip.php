<?php
// Consento le richieste da qualsiasi origine
header("Access-Control-Allow-Origin: http://localhost:5173");
// Consento metodi specifici (GET, POST, ecc.)
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
// Consento specifici header nella richiesta
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Specifico che la risposta del server sarà in formato JSON
header('Content-Type: application/json');


//Memorizzo nella variabile $input il corpo della richiesta HTTP
$input = file_get_contents('php://input');
//Decodifico da formato JSON in un array associativo PHP e assegno alla variabile $data
$data = json_decode($input, true);

// Aggiungo un controllo per assicurarmi che i dati siano stati effettivamente ricevuti
if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Dati non ricevuti']);
    exit;
}
//Controllo che le chiavi esistano nell'array $data
if (!isset($data['destination']) || !isset($data['duration']) || !isset($data['start_date'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dati mancanti']);
    exit;
}

//Recupero le date dal form
$startDate = $data['start_date'];
$endDate = $data['end_date'];
$duration = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) + 1;

//Creo un nuovo viaggio
$newTrip = [
    //Assegno un id univoco
    'id' => uniqid(),
    //Recupero i campi inviati dal form
    'destination' => $data['destination'],
    'start_date' => $data['start_date'],
    /*Aggiungio la duarata del viaggio alla data di 
    inizio per calcolare la data finale del viaggio*/
    'end_date' => $data['end_date'],
    'duration' => $duration,
    'days' => [],
];
//Inizializzo l'array di tappe per ogni giornata
for ($i = 0; $i < $duration; $i++) {
    //Eseguo il push dell'array vuoto stops
    $newTrip['days'][] = ['stops' => []];
}


//Assegno alla variabile il percorso del file json
$dataFile = 'data/trips.json';
//Leggo i dati esistenti
$trips = [];
if (file_exists($dataFile)) {
    /* json_decode decodifica la stringa json letta 
        dal file e la converte in un array associativo PHP,
        l'argomento true specifica che il json deve essere
        convertito in un array.*/
    $trips = json_decode(file_get_contents($dataFile), true);
}

//Salvo i dati
//Aggiungo il viaggio all'array dei viaggi
$trips[] = $newTrip;
/*Scrivo la stringa JSON nel percorso di $dataFile,
 se il file non esiste viene creato*/
file_put_contents($dataFile, json_encode($trips));
//Creo file per i dettagli del viaggio
//Costruisco il nome del file per i dettagli del viaggio
$tripDetailsFile = 'data/trip_' . $newTrip['id'] . '.json';
/*Scrivo la stringa JSON nel percorso di $tripDeatilsFile,
 se il file non esiste viene creato*/
file_put_contents($tripDetailsFile, json_encode($newTrip));
//Risposta JSON che indica il successo dell'operazione
echo json_encode(['status' => 'success', 'trip_id' => $newTrip['id']]);
