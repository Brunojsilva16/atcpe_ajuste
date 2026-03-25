<?php

namespace App\Models;

use App\Database\Connection;
use PDO;

class HomeModel
{

    private $conn;

    public function __construct()
    {
        // Conecta ao banco diretamente usando a classe Connection
        $this->conn = Connection::getInstance();
    }

 
}
