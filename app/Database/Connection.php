<?php

namespace App\Database;

use PDO;
use PDOException;

class Connection
{
    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            try {
                // Credenciais baseadas no ambiente local padrão ou variáveis de ambiente
                // No arquivo env do user original as configs devem ser ajustadas
                
                $host = $_ENV['DB_HOST'];
                $dbname = $_ENV['DB_DATABASE']; // Nome baseado no SQL enviado
                $user = $_ENV['DB_USERNAME'];
                $pass = $_ENV['DB_PASSWORD'];
                $port = $_ENV['DB_PORT'];

                self::$instance = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
            } catch (PDOException $e) {
                // Em produção, logar o erro em arquivo e mostrar mensagem genérica
                die("Erro de conexão com o banco de dados: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}