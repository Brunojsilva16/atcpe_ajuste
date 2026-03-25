<?php

namespace App\Models;

use App\Database\Connection;
use PDO;

class SearchModel
{
    private $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Realiza a busca no banco de dados utilizando os filtros passados pelo Controller.
     * * @param array $filters Array contendo os critérios de busca.
     * @return array Lista de profissionais encontrados.
     */
    public function searchProfessionals(array $filters)
    {
        // Query base: Apenas ativos e profissionais/psiquiatras
        $sql = "SELECT * FROM associados_25 WHERE id_status = 1 AND tipo_ass IN ('Profissional', 'Psiquiatra')";

        $conditions = [];
        $params = [];

        // 1. Filtro por Nome (Busca em nome_completo ou nomever)
        if (!empty($filters['nome'])) {
            $conditions[] = "(nome_completo LIKE ? OR nomever LIKE ?)";
            $termo = '%' . $filters['nome'] . '%';
            $params[] = $termo;
            $params[] = $termo;
        }

        // 2. Filtro por Público de Atendimento
        if (!empty($filters['publico_atend']) && $filters['publico_atend'] !== 'Todos') {
            $conditions[] = "publico_atend LIKE ?";
            $params[] = '%' . $filters['publico_atend'] . '%';
        }

        // 3. Filtro por Modalidade
        if (!empty($filters['mod_atendimento']) && $filters['mod_atendimento'] !== 'Todos') {
            $conditions[] = "modalidade = ?";
            $params[] = $filters['mod_atendimento'];
        }

        // 4. Filtro por Plano de Saúde (Coluna 'plano_s' conforme mapeamento anterior)
        if (!empty($filters['plano_saude']) && $filters['plano_saude'] !== 'Todos') {
            $conditions[] = "plano_s = ?";
            $params[] = $filters['plano_saude'];
        }

        // 5. Filtro por Cidade
        if (!empty($filters['cidade'])) {
            $conditions[] = "cidade_at LIKE ?";
            $params[] = '%' . $filters['cidade'] . '%';
        }

        // 6. Filtro por Bairro
        if (!empty($filters['bairro'])) {
            $conditions[] = "bairro_at LIKE ?";
            $params[] = '%' . $filters['bairro'] . '%';
        }

        // 7. Filtro por Tipo (Psicólogo / Psiquiatra)
        if (!empty($filters['tipo']) && $filters['tipo'] !== 'Todos') {
            $conditions[] = "tipo_ass = ?";
            $params[] = $filters['tipo'];
        }

        // Montagem dinâmica da query
        if (count($conditions) > 0) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        // Ordenação por nome para exibição amigável
        $sql .= " ORDER BY nomever ASC, nome_completo ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}