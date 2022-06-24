<?php
session_start(); //session initialization
    class Dbh{
        protected $host = "127.0.0.1";
        protected $user = "root";
        protected $password = "";
        protected $db = "zuriphp";
        
        protected function connect() {
            #create connetion
            $conn = new mysqli(
                $this->host,
                $this->user,
                $this->password,
                $this->db);
            #check connection
            if(!$conn){
                echo "<script> alert('Error connecting to the database') </script>";
            } else {
                return $conn;
            }
        }
    }
?>