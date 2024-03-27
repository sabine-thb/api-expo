<?php





date_default_timezone_set('Europe/Paris'); // Définit le fuseau horaire sur celui de Paris

        

// Vérification des champs obligatoires

if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['mail']) || empty($_POST['tickets']) || empty($_POST['date']) || empty($_POST['horaire'])) {

    // Au moins un des champs obligatoires est vide

    header('Location: billeterie.php?err=empty');

    var_dump($_POST);

    exit(); // Arrêter le script

} else {

    // Tous les champs obligatoires sont remplis, vous pouvez continuer le traitement

    $nom = $_POST['nom'];

    $prenom = $_POST['prenom'];

    $dateResa = $_POST['dateResa'];

    $mail = $_POST['mail'];

    $tickets = $_POST['tickets'];

    $date = $_POST['date'];

    $horaire = $_POST['horaire']; // Correction de la variable $heure à $horaire



    // Vérification de l'adresse email

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {

        // L'adresse email n'est pas valide

        header('Location: billeterie.php?err=mail');

        exit(); // Arrêter le script

    }



    // Vérification de la date

    $dateActuelle = new DateTime();

    $dateChoisie = DateTime::createFromFormat('Y-m-d', $date); // Supposant que votre date est au format YYYY-MM-DD



    if ($dateChoisie === false || $dateChoisie < $dateActuelle) {

        // La date n'est pas valide ou est antérieure à la date actuelle

        header('Location: billeterie.php?err=date');

        exit(); // Arrêter le script

    }



    // La date est valide, vous pouvez continuer le traitement



    // Création du tableau des données à envoyer à l'API

    $reservationData = array(

        'nom' => $nom,

        'prenom' => $prenom,

        'dateResa' => $dateResa, // Date et heure actuelles

        'mail' => $mail,

        'tickets' => $tickets,

        'date' => $date,

        'horaire' => $horaire

    );



    // // Convertir le tableau en format JSON

    $jsonData = json_encode($reservationData);



    // // URL de votre API

    $apiUrl = 'https://api-expo.esprit-vigee.com/index.php/reservation';



    // // Initialisation de cURL

    $curl = curl_init($apiUrl);



    // // Configuration de la requête cURL

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_POST, true);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);

    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));



    // // Exécution de la requête

    $response = curl_exec($curl);

    

    var_dump($response);





    // // Vérification des éventuelles erreurs cURL

        if ($response === false) {

            echo 'Erreur cURL : ' . curl_error($curl);

        }



    //     // Affichage de la réponse de l'API (si nécessaire)

        echo 'Réponse de l\'API : ' . $response;



    // // Fermeture de la session cURL

    curl_close($curl);



    // // Traitement de la réponse de l'API (si nécessaire)

    // // Vous pouvez vérifier $response pour voir la réponse de l'API



    // Redirection vers la page de succès

    // header('Location: billeterie.php?resa=ok');

    echo"<a href='billeterie.php?resa=ok' >Retour billeterie</a>";

    exit(); // Arrêter le script

}



?>

