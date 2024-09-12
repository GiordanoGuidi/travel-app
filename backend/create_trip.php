<?php
// Consento le richieste da qualsiasi origine
header("Access-Control-Allow-Origin: *");
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
if (!isset($data['destination']) || !isset($data['start_date']) || !isset($data['end_date'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dati mancanti']);
    exit;
}
//#Eseguo la validazione dei dati del form
$errors = [];
//Controllo che la destinazione non sia vuota
if (empty(trim($data['destination']))) {
    $errors[] = 'La destinazione non può essere vuota';
}
//Controllo che la destinazione non contenga numeri e/o caratteri speciali
else if (!preg_match("/^[a-zA-Z\s]+$/", $data['destination'])) {
    $errors[] = 'La destinazione non può contenere numeri e/o caratteri speciali';
}
//Recupero la data di inizio viaggio
$start_date = new DateTime($data['start_date']);
// Normalizza la data di start_date alla mezzanotte
$start_date->setTime(0, 0);
//Recupero la data odierna
$current_date = new DateTime();
// Normalizzo la data odierna alla mezzanotte
$current_date->setTime(0, 0);
//Recupero la data fine viaggio
$end_date = new DateTime($data['end_date']);
$end_date->setTime(0, 0);
//Controlle che le date siano inviate in formato valido
if (!$start_date || !$end_date) {
    $errors[] = "Le date devono essere valide";
} else if ($start_date < $current_date) {
    $errors[] = "La data di inizio non può essere una data passata";
}
//La data di inizio deve essere antecedente la data di rientro
else if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
    $errors[] = "La data di inizio non può essere successiva alla data di fine";
}
//! Se ci sono errori nella validazione restituisco questa risposta
if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'message' => 'Validazione fallita', 'errors' => $errors]);
}
//#Altrimenti proseguo con il salvataggio dei dati
else {
    //Formatto le date
    $formattedStartDate = $start_date->format('d-m-Y');
    $formattedEndDate = $end_date->format('d-m-Y');
    //Calcolo la differenza tra le date
    $interval = $start_date->diff($end_date);
    // Recupero il numero di giorni
    $duration = $interval->days + 1;

    //Creo un nuovo viaggio
    $newTrip = [
        //Assegno un id univoco
        'id' => uniqid(),
        //Recupero i campi inviati dal form
        'destination' => $data['destination'],
        'start_date' => $formattedStartDate,
        /*Aggiungio la duarata del viaggio alla data di 
        inizio per calcolare la data finale del viaggio*/
        'end_date' => $formattedEndDate,
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
}
