<?php

namespace App\Models;

use App\Database\Connection;
use PDO;

class CarouselModel
{
    private $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Busca todos os slides ativos ordenados pela ordem definida
     */
    public function getSlides()
    {
        // Ajuste o nome da tabela ('carrossel' ou 'carousel') conforme seu banco de dados
        // Ajuste os nomes das colunas (imagem, titulo, link, ordem, status) conforme seu banco
        $sql = "SELECT * FROM associados_25 WHERE id_status = 1 AND tipo_ass IN ('Profissional', 'Psiquiatra')";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Em produção, logar o erro em vez de mostrar
            error_log("Erro ao buscar carrossel: " . $e->getMessage());
            return [];
        }
    }
}
