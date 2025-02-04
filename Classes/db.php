<?php

class DB
{
    private $host = "localhost:8080";
    private $username = "root";
    private $password = "";
    private $dbname = "simpleprac";

    // Make this method public so it can be accessed from outside the class
    public function connect()
    {
        $conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    public function read($query)
    {
        $conn = $this->connect();
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $conn->close();
            return $data;
        }

        $conn->close();
        return [];
    }

    public function save($query)
    {
        $conn = $this->connect();
        $result = $conn->query($query);
        $conn->close();

        return $result;
    }
}
?>
