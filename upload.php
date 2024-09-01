<?php
// Consento le richieste dal dominio del frontend
header("Access-Control-Allow-Origin: https://prao-travel.netlify.app");
// Consento metodi specifici (GET, POST, ecc.)
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
// Consento specifici header nella richiesta
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Specifico che la risposta del server sarà in formato JSON
header('Content-Type: application/json');

//Imposto la directory di destinazione per i file caricati
$target_dir = "uploads/";

//Verifico i permessi della cartella
if (file_exists($target_dir)) {
    $perms = fileperms($target_dir);
} else {
    echo json_encode(["success" => false, "message" => "La cartella non esiste."]);
    exit;
}
// Verifico se la cartella esiste, se non esiste, creo la cartella
if (!is_dir($target_dir)) {
    if (!mkdir($target_dir, 0755, true)) {
        echo json_encode(["success" => false, "message" => "Errore nella creazione della cartella di destinazione."]);
        exit;
    }
}
// Verifica che il file sia stato caricato correttamente
if (!isset($_FILES["image"]) || $_FILES["image"]["error"] != UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "message" => "Errore nel caricamento del file."]);
    exit;
}

//Percorso completo dove il file sarà salvato
$target_file = $target_dir . basename($_FILES["image"]["name"]);
//Recupero l'estensione del file
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Controllo se l'immagine è effettivamente un'immagine
$check = getimagesize($_FILES["image"]["tmp_name"]);
if ($check === false) {
    echo json_encode(["success" => false, "message" => "Il file non è un'immagine."]);
    exit;
}
// Consenti solo determinati formati di file
$allowed_extensions = ['jpg', 'jpeg', 'png'];
if (!in_array($imageFileType, $allowed_extensions)) {
    echo json_encode(["success" => false, "message" => "Sono permessi solo file JPG, JPEG, PNG."]);
    exit;
}


// Provo a caricare il file
if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    echo json_encode(["success" => true, "filePath" => $target_file]);
} else {
    error_log("Errore nel caricamento del file: " . $_FILES["image"]["name"]);
    echo json_encode(["success" => false, "message" => "C'è stato un errore durante il caricamento del file."]);
}
