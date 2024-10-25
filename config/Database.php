<?php
class Database{
    private $conn;

    public function getConnection(){
        $this->conn=new mysqli("localhost","root","","event_scheduling_system");

        if ($this->conn->connect_error){
            die("Connection error".$this->conn->connect_error);
        }
        return $this->conn;
    }
}