<?php

class Database
{
    private $servername = "localhost:8080";
    private $username = "root"; // Change to your database username
    private $password = ""; // Change to your database password
    private $dbname = "simpleprac"; // Change to your database name
    private $conn;

    // Establish a database connection
    public function connect()
    {
        if ($this->conn == null) {
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

            // Check connection
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }
        return $this->conn;
    }

    // Function to execute a read query (SELECT)
    public function read($query)
    {
        $conn = $this->connect(); // Ensure the connection is established
        $result = $conn->query($query);

        if (!$result) {
            return false;
        } else {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    // Function to execute a write query (INSERT, UPDATE, DELETE)
    public function save($query)
    {
        $conn = $this->connect(); // Ensure the connection is established
        $result = $conn->query($query);

        if (!$result) {
            return false;
        } else {
            return true;
        }
    }
}

?>
