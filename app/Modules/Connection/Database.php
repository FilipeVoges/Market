<?php


namespace App\Modules\Connection;

class Database
{
    private $server = BD_SERVER;
    protected $database = BD_DATABASE;
    private $user = BD_USER;
    private $password = BD_PASSWORD;
    private $driver = BD_DRIVER;
    private $db;
    private static $instance;

    public function __construct()
    {
        $this->server = BD_SERVER;
        $this->database = BD_DATABASE;
        $this->user = BD_USER;
        $this->password = BD_PASSWORD;
        $this->driver = BD_DRIVER;

        $this->connect();
    }

    public static function getInstance() {
        self::$instance = new Database();

        return self::$instance;
    }

    public function __destruct() {
        $this->disconnect();
    }

    private function __clone() {}
    private function __wakeup() {}

    public function setServer($server) {
        $this->server = $server;
    }

    public function setDatabase($database) {
        $this->database = $database;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    private function getServer() {
        return $this->server;
    }


    protected function getDatabase() {
        return $this->database;
    }

    private function getUser() {
        return $this->user;
    }

    private function getPassword() {
        return $this->password;
    }

    public function getLastInsertId() {
        if(is_null($this->db)) {
            $this->connect();
        }

        return $this->db->lastInsertId();
    }

    private function connect() {
        try{
            $this->db = new PDO("$this->driver:host=$this->server;dbname=$this->database;charset=utf8mb4;", $this->user, $this->password);
        }  catch (PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    public function getQuery($sql = null)
    {
        if(is_null($sql)){
            throw new \Exception('SQL não informado!');
        }

        if(is_null($this->db)){
            $this->connect();
        }

        $query = $this->db->query($sql);
        if(!$query){
            throw new Exception("Erro de SQL." . "-> {$sql} -> " . $this->getErrorMessage(), 500);
        }

        return $query;
    }

    public function getErrorMessage(){
        if(is_null($this->db)){
            $this->connect();
        }

        $errorInfo = $this->db->errorInfo();
        return $errorInfo[2];

    }

    public function getFetchRow($query)
    {
        return $query->fetch(PDO::FETCH_BOTH);
    }

    public function getFetchArray($query)
    {
        return $query->fetch(PDO::FETCH_BOTH);
    }

    public function getFetchAssoc($query)
    {
        return  $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getFetchObject($query)
    {
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getTotal($stmt){
        $total = $this->getFetchAll($stmt);

        return count($total);
    }

    /**
     *
     * @param string $type (possible values: array, assoc, object)
     * @param $query
     * @return void
     */
    public function getFetchAll($stmt, $type = 'assoc')
    {
        $return = [];
        switch ($type) {
            case 'array':
                while($row = $this->getFetchArray($stmt)){
                    $return[] = $row;
                }
                break;
            case 'object':
                while($row = $this->getFetchObject($stmt)){
                    $return[] = $row;
                }
                break;
            case 'assoc':
            default:
                while($row = $this->getFetchAssoc($stmt)){
                    $return[] = $row;
                }
                break;
        }
        return $return;
    }

    public function getAffectedRows($query)
    {
        return $query->rowCount();
    }

    public function clear($query)
    {
        $query->closeCursor();
    }

    public function disconnect() {
        if (isset($this->db)) {
            $this->db = null;
        }
    }
}
