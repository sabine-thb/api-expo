<?php
require_once './src/Database.php';
require_once './src/ResaController.php';

header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  header("Access-Control-Allow-Headers: Content-Type, Authorization");
  header("HTTP/1.1 200 OK");
  exit;
}

$parts = explode('/', $_SERVER['REQUEST_URI']);

if ($parts[3] == 'reservation') {
  $id = $parts[4] ?? null;
  $database = new Database('localhost', 'esprit_vigee', 'root', '', 3306);
  try {
    $pdo = $database->getConnection();
  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
      'message' => 'Database connection error'
    ]);
    exit;
  }
  
  $query = new ResaQuery($pdo);
  $db = $pdo; // Assigner la connexion PDO à la variable $db
  $controller = new ResaController($query, $db);
  $controller->processRequest($_SERVER['REQUEST_METHOD'], $id);


  

} elseif ($parts[3] == 'connexion' && $_SERVER['REQUEST_METHOD'] == 'POST') {
  // Endpoint for login
  if (!isset($_POST['login']) || !isset($_POST['mdp'])) {
    http_response_code(400);
    echo json_encode([
      'message' => 'Missing login or password'
    ]);
    exit;
  }

  $login = $_POST['login'];
  $mdp = $_POST['mdp'];
  
  $database = new Database('localhost', 'esprit_vigee', 'root', '', 3306);
  try {
    $pdo = $database->getConnection();
  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
      'message' => 'Database connection error'
    ]);
    exit;
  }

  $db = $pdo; // Assigner la connexion PDO à la variable $db
  $query = new ResaQuery($pdo);
  $controller = new ResaController($query, $db);
  $result = $controller->Connexion($login, $mdp);


  // http_response_code($result['code']);
  echo json_encode($result);
} elseif ($parts[3] == 'deconnexion' && $_SERVER['REQUEST_METHOD'] == 'POST') {
  // Endpoint for logout
  Deconnexion(); // Appel de la fonction de déconnexion
} else {
  http_response_code(404);
  echo json_encode([
    'message' => 'Endpoint not found'
  ]);
}
?>
