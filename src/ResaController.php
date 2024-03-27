<?php
require_once './src/ResaQuery.php';
require_once './src/Database.php';

class ResaController
{

  private $query;
  private $db;

  public function __construct($query, $db)
  {
      $this->query = $query;
      $this->db = $db;
  }
  
  public function processRequest($method, $id = null)
  {
    if ($id) {
      $this->processSingleElementRequest($method, $id);
    } else {
      $this->processCollectionRequest($method);
    }
  }

  private function processSingleElementRequest($method, $id) {
    $reservation = $this->query->get($id);
    if (!$reservation) {
      http_response_code(404);
      echo json_encode([
        'message' => 'Reservation not found'
      ]);
      return;
    }

    switch ($method) {
      case 'GET':
        echo json_encode($reservation);
        break;
      case 'DELETE':
        $rows = $this->query->remove($id);
        echo json_encode([
          'message' => 'Reservation was successfully deleted',
          'nb_rows' => $rows
        ]);
        break;
      case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $errors = $this->getValidationErrors($data, false);
        if (!empty($errors)) {
          http_response_code(422);
          echo json_encode([
            'message' => 'Validation errors',
            'errors' => $errors
          ]);
          break;
        }

        $this->query->update($reservation, $data);
        echo json_encode([
          'message' => 'Reservation with id ' . $id . ' was successfully updated'
        ]);
        
        break;
      default:
        http_response_code(405);
        header('Allow: GET, DELETE, PUT');
        echo json_encode([
          'message' => 'Method not allowed'
        ]);
    }
  }

  function Connexion($login, $mdp)
{
    global $db; 

    if (!$db) {
        return array('success' => false, 'code' => 500, 'message' => "Erreur de connexion à la base de données");
    }

    // Préparez la requête pour sélectionner l'administrateur avec le login donné
    $req = $db->prepare('SELECT * FROM administrateur WHERE login = ?');
    $req->execute(array($login));
    $result = $req->fetch();

    // return ($result['mdp']);

    // Vérifiez si un résultat a été trouvé
    if ($result) {
        // Vérifiez si le mot de passe correspond
        if (password_verify($mdp, $result["mdp"])) {
            // Mot de passe correct
            session_start();
            $_SESSION['admin_id'] = $result['id'];
            $_SESSION['admin_login'] = $result['login'];
            return array('success' => true, 'code' => 200);
        } else {
            // Mot de passe incorrect
            return array('success' => false, 'code' => 401, 'message' => "Mot de passe incorrect");
        }
    } else {
        // Aucun résultat trouvé pour le login donné
        return array('success' => false, 'code' => 404, 'message' => "Aucun utilisateur trouvé pour le login donné");
    }
}


function Deconnexion() {
  // Démarrez la session pour accéder aux variables de session
  session_start();

  // Détruire toutes les variables de session
  $_SESSION = array();

  if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
      );
  }

  // Finalement, détruire la session
  session_destroy();
  header("Location: /login.php"); // Rediriger vers la page de connexion
  exit(); 
}


   function sendMail($data) {
      $to = $data['mail'];
      $subject = 'Réservation Esprit Vigée';
      $message = '
      <div style="background-image:url(https://expo-vigee.thibout.butmmi.o2switch.site/styles/images/backgroundMail.jpg); background-size:cover; background-position:center; padding:20px; width:fit-content; height:auto;" position:relative;>
      <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4);"></div>
      <div style="text-align: center;">
          <img src="https://expo-vigee.thibout.butmmi.o2switch.site/styles/images/logo.png" alt="logo" style="margin: 0 auto; width:150px">
      </div>
        <h1 style="color:white;font-style:italic;font-size:3rem;">Merci ' . $data['prenom'] . ' !</h1>
        <h2 style="color:white;">Ta réservation a bien été prise en compte.</h2><hr style="margin:2rem"r >
        <h3 style="color:white">Détails de la visite :</h3>
            <article>
              <h4 style="color:white;margin:0">Le <b>' . $data['date'] . '</b> à <b>' . $data['horaire'] . '</b>
              </h4>
              <h4 style="color:white;margin:0">Plein tarif - Gratuit</h4>
              <h4 style="color:white;margin:0">Nombre de tickets : ' . $data['tickets'] . '</h4>
            </article><hr style="margin:2rem">
            <p style="color:white">Besoin d\'un renseignement ? Contactez-nous à l\'adresse <b style="color:white;">contact@esprit-vigee.com</b>. Nous vous répondrons dans les plus brefs délais.</p>
        <h3 style="color:white;">Bonne visite !</h3>
      </div>';
   
   
      

      // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
      $headers[] = 'MIME-Version: 1.0';
      $headers[] = 'Content-type: text/html; charset=iso-8859-1';

      // En-têtes additionnels
      $headers[] = 'From:sabine@thibout.fr';

      // Envoi
      mail($to, $subject, $message, implode("\r\n", $headers));

    }


    private function processCollectionRequest($method) {
      switch ($method) {
         case 'GET':
           $reservations = $this->query->getAll();
           echo json_encode($reservations);
           break;
         case 'POST';
           $data = json_decode(file_get_contents('php://input'), true);
           if (empty($data) && !empty($_POST)) {
             $data = $_POST;
           }
           $errors = $this->getValidationErrors($data);
           if (!empty($errors)) {
             http_response_code(422);
             echo json_encode([
               'message' => 'Validation errors',
               'errors' => $errors
             ]);
             break;
           }
   
           $id = $this->query->create($data);
           http_response_code(201);
           $this->sendMail($data);
           echo json_encode([
             'message' => 'Reservation created',
             'id' => $id
           ]);
           break;
         default:
           http_response_code(405);
           header('Allow: GET, POST');
           echo json_encode([
             'message' => 'Method not allowed'
           ]);
       }
     }

  private function getValidationErrors($data, $new_object = true) {
    $errors = [];
    // Vérifier aussi le bon format des données : par exemple ici que hp est bien un int
    if ($new_object) {
      if (!isset($data['nom']) || empty($data['nom'])) {
        $errors['nom'] = 'Nom is required';
      }
      if (!isset($data['prenom']) || empty($data['prenom'])) {
        $errors['prenom'] = 'Prenom is required'; // Correction ici
      }
      if (!isset($data['mail']) || empty($data['mail'])) {
        $errors['mail'] = 'Mail is required'; // Correction ici
      }
      if (!isset($data['tickets']) || empty($data['tickets'])) {
        $errors['tickets'] = 'Tickets is required'; // Correction ici
      }
      if (!isset($data['date']) || empty($data['date'])) {
        $errors['date'] = 'Date is required'; // Correction ici
      }
      if (!isset($data['horaire']) || empty($data['horaire'])) {
        $errors['horaire'] = 'Horaire is required'; // Correction ici
      }
      
    }
    return $errors;
  }

}