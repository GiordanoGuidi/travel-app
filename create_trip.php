<?php
//Controllo se la richiesta è di tipo POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Assegno alla variabile il percorso del file json
    $dataFile = 'data/trips.json';
    //Leggo i dati esistenti
    $trips = [];
    if (file_exists($dataFile)) {
        /* json_decode decodifica la stringa json letta 
        dal file e la converte in un array associativo PHP,
        l'argomento true specifica che il json deve essere
        convertito in un array.L'array viene assegnato alla 
        variabile $trips*/
        $trips = json_decode(file_get_contents($dataFile), true);
    }
    //Creo un nuovo viaggio
    $newTrip = [
        //Assegno un id univoco
        'id' => uniqid(),
        //Recupero i campi inviati dal form
        'destination' => $_POST['destination'],
        'duration' => (int)$POST['duration'],
        'start_date' => $_POST['start_date'],
        /*Aggiungio la duarata del viaggio alla data di 
        inizio per calcolare la data finale del viaggio*/
        'end_date' => date('Y-m-d', strtotime("+{$_POST['duration']} days", strtotime($_POST['start_date']))),
        'days' => [],
    ];
    //Salvo i dati
    //Aggiungo il viaggio all'array dei viaggi
    $trips[] = $newTrip;
    /*Scrivo la stringa JSON nel percorso di $dataFile 
    dopo aver convertito $trips in formato JSON*/
    file_put_contents($dataFile, json_encode($trips));
    //Creo file per i dettagli del viaggio
    //Costruisco il nome del file per i dettagli del viaggio
    $tripDetailsFile = 'data/trip_' . $newTrip['id'] . '.json';
    file_put_contents($tripDetailsFile, json_encode($newTrip));
    //Risposta JSON che indica il successo dell'operazione
    echo json_encode(['status' => 'success', 'trip_id' => $newTrip['id']]);
}
