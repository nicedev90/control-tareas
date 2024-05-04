<?php

class Database {

    protected $db;
    protected const DB_HOST = '127.0.0.1';
    protected const DB_USER = 'root';    
    protected const DB_PASS = '';
    protected const DB_NAME = 'dev_classroom';


    private $stmt;

    public function __construct() {

        $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";
        try {
            $this->db = new PDO($dsn, self::DB_USER, self::DB_PASS);

            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->db;
        } catch(Exception $e) {
            die("Error al conectar con la base de datos." . $e);
        }
    }

    public function prepare($sql) {
        $this->stmt = $this->db->prepare($sql);
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function fetch() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function fetchAll() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function beginTransaction() {
        return $this->db->beginTransaction();
    }

    public function commit() {
        return $this->db->commit();
    }

    public function rollBack() {
        return $this->db->rollBack();
    }

}
