
<?php
require_once './src/Database.php';
class ResaQuery
{

  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function getAll()
  {
    $sql = 'SELECT * FROM reservation';
    $statement = $this->pdo->prepare($sql);
    $statement->execute();
    
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public function create($data)
  {
    $sql = 'INSERT INTO reservation (nom, prenom, dateResa, mail, tickets, date, horaire) VALUES (:nom, :prenom, :dateResa, :mail, :tickets, :date, :horaire)';
    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':nom', $data['nom'], PDO::PARAM_STR);
    $statement->bindValue(':prenom', $data['prenom'], PDO::PARAM_STR);
    $statement->bindValue(':dateResa', $data['dateResa'], PDO::PARAM_STR);
    $statement->bindValue(':mail', $data['mail'], PDO::PARAM_STR);
    $statement->bindValue(':tickets', $data['tickets'], PDO::PARAM_INT);
    $statement->bindValue(':date', $data['date'], PDO::PARAM_STR);
    $statement->bindValue(':horaire', $data['horaire'], PDO::PARAM_STR);
    $statement->execute();

    return $this->pdo->lastInsertId();
  }


  

  public function get($id)
  {
    $sql = 'SELECT * FROM reservation WHERE id = :id';
    $statement = $this->pdo->prepare($sql);
    $statement->bindParam(':id', $id);
    $statement->execute();

    $data = $statement->fetch(PDO::FETCH_ASSOC);

    return $data;
  }

  public function remove($id)
  {
    $sql = 'DELETE FROM reservation WHERE id = :id';
    $statement = $this->pdo->prepare($sql);
    $statement->bindParam(':id', $id);
    $statement->execute();

    // Retourne le nombre de lignes effacées
    return $statement->rowCount();
  }



    public function update($reservation, $data)
    {
    $sql = 'UPDATE reservation set nom = :nom, prenom = :prenom, dateResa=:dateResa, mail = :mail, tickets = :tickets, date = :date, horaire = :horaire WHERE id = :id';
    $statement = $this->pdo->prepare($sql);
    $nom = $data['nom'] ? $data['nom'] : $reservation['name'];
    $prenom = $data['prenom'] ? $data['prenom'] : $reservation['prenom'];
    $dateResa = $data['dateResa'] ? $data['dateResa'] : $reservation['dateResa'];
    $mail = $data['mail'] ? $data['mail'] : $reservation['mail'];
    $tickets = $data['tickets'] ? $data['tickets'] : $reservation['tickets'];
    $date = $data['date'] ? $data['date'] : $reservation['date'];
    $horaire = $data['horaire'] ? $data['horaire'] : $reservation['horaire'];
    $id = $data['id'] ? $data['id'] : $reservation['id'];
    $statement->bindParam(':nom', $nom);
    $statement->bindParam(':prenom', $prenom);
    $statement->bindParam(':dateResa', $dateResa);
    $statement->bindParam(':mail', $mail);
    $statement->bindParam(':tickets', $tickets);
    $statement->bindParam(':date', $date);
    $statement->bindParam(':horaire', $horaire);
    $statement->bindParam(':id', $reservation['id']);

    $statement->execute();
  }
}