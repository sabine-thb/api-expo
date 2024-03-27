<?php
class Database
{

  private $host;
  private $db_name;
  private $user;
  private $password;
  private $port;
  
  public function __construct($host, $db_name, $user, $password, $port)
  {
    $this->host = $host;
    $this->db_name = $db_name;
    $this->user = $user;
    $this->password = $password;
    $this->port = $port;
  }

  public function getConnection()
  {
    $dsn = "mysql:host={$this->host};dbname={$this->db_name};port={$this->port}";

    return new PDO($dsn, $this->user, $this->password, [
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_STRINGIFY_FETCHES => false
    ]);
  }
}