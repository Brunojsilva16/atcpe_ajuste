<?php

namespace App\Models;

use App\Database\Connection;
use PDO;

class AssociadoModel
{
    private $db;
    private $table = 'associados_25';

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Retorna a lista de associados com suporte a filtros e paginação
     */
    public function getAllAssociates($filter = 'geral', $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        $conditions = [];
        $params = [];

        if ($filter == 'ativos') {
            $conditions[] = "id_status = 1";
        } elseif ($filter == 'inativos') {
            $conditions[] = "id_status = 0";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY nomever ASC, nome_completo ASC";

        // Ajuste: Paginação
        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($sql);

        if ($limit !== null && $offset !== null) {
            // Importante: Bind como Inteiro para o MySQL não dar erro de sintaxe no LIMIT
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Conta o total de associados com base em um filtro
     */
    public function countAllAssociates($filter = 'geral')
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";

        if ($filter == 'ativos') {
            $sql .= " WHERE id_status = 1";
        } elseif ($filter == 'inativos') {
            $sql .= " WHERE id_status = 0";
        }

        $stmt = $this->db->query($sql);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Cria um novo associado
     */
    public function create(array $data)
    {
        // Adicionado: tipo_ass
        $sql = "INSERT INTO {$this->table} (nome_completo,
            nomever, email, senha, celular, rede_social, 
            cep, endereco, numero, bairro_at, cidade_at, uf, 
            publico_atend, modalidade, acomp_terapeutico, mini_curr, 
            tipo_ass, crp_crm, foto, id_status, data_cad, user_tipo
        ) VALUES (
            :nome_completo, :nomever, :email, :senha, :celular, :rede_social,
            :cep, :endereco, :numero, :bairro_at, :cidade_at, :uf,
            :publico_atend, :modalidade, :acomp_terapeutico, :mini_curr,
            :tipo_ass, :crp_crm, :foto, 1, NOW(), 0
        )";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':nome_completo', $data['nome_completo']);
        $stmt->bindValue(':nomever', $data['nomever']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':senha', $data['senha']);
        $stmt->bindValue(':celular', $data['celular']);
        $stmt->bindValue(':rede_social', $data['rede_social']);
        $stmt->bindValue(':cep', $data['cep']);
        $stmt->bindValue(':endereco', $data['endereco']);
        $stmt->bindValue(':numero', $data['numero']);
        $stmt->bindValue(':bairro_at', $data['bairro_at']);
        $stmt->bindValue(':cidade_at', $data['cidade_at']);
        $stmt->bindValue(':uf', $data['uf']);
        $stmt->bindValue(':publico_atend', $data['publico_atend']);
        $stmt->bindValue(':modalidade', $data['modalidade']);
        $stmt->bindValue(':acomp_terapeutico', $data['acomp_terapeutico']);
        $stmt->bindValue(':mini_curr', $data['mini_curr']);
        // Novo Bind
        $stmt->bindValue(':tipo_ass', $data['tipo_ass']);
        $stmt->bindValue(':crp_crm', $data['crp_crm']);

        $stmt->bindValue(':foto', $data['foto']);

        return $stmt->execute();
    }

    // =================================================================
    // ÁREA DE AUTENTICAÇÃO (LOGIN)
    // =================================================================

    /**
     * Busca um associado pelo email (Login)
     */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um associado pelo ID (Edição/Dashboard)
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_associados = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lista todos os associados para o Dashboard Admin
     */
    // public function getAllAssociates($filter = 'geral')
    // {
    //     $sql = "SELECT * FROM {$this->table}";

    //     if ($filter === 'ativos') {
    //         $sql .= " WHERE id_status = 1";
    //     } elseif ($filter === 'inativos') {
    //         $sql .= " WHERE id_status = 0";
    //     }

    //     // Ordena por nomever (prioridade) ou nome
    //     $sql .= " ORDER BY nomever ASC, nome_completo ASC";

    //     $stmt = $this->db->query($sql);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    /**
     * Atualiza dados do perfil
     */
    public function updateProfile($id, array $data)
    {
        $fields = [
            "nome_completo = :nome_completo",
            "nomever = :nomever",
            "celular = :celular",
            "rede_social = :rede_social",
            "cep = :cep",
            "endereco = :endereco",
            "numero = :numero",
            "bairro_at = :bairro_at",
            "cidade_at = :cidade_at",
            "uf = :uf",
            "publico_atend = :publico_atend",
            "modalidade = :modalidade",
            "acomp_terapeutico = :acomp_terapeutico",
            "mini_curr = :mini_curr",
            "tipo_ass = :tipo_ass",
            "crp_crm = :crp_crm"
        ];

        // Adiciona a foto apenas se ela foi enviada no array de dados
        if (isset($data['foto'])) {
            $fields[] = "foto = :foto";
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id_associados = :id";

        $stmt = $this->db->prepare($sql);

        // Bind dos campos obrigatórios
        $stmt->bindValue(':nome_completo', $data['nome_completo']);
        $stmt->bindValue(':nomever', $data['nomever']);
        $stmt->bindValue(':celular', $data['celular']);
        $stmt->bindValue(':rede_social', $data['rede_social']);
        $stmt->bindValue(':cep', $data['cep']);
        $stmt->bindValue(':endereco', $data['endereco']);
        $stmt->bindValue(':numero', $data['numero']);
        $stmt->bindValue(':bairro_at', $data['bairro_at']);
        $stmt->bindValue(':cidade_at', $data['cidade_at']);
        $stmt->bindValue(':uf', $data['uf']);
        $stmt->bindValue(':publico_atend', $data['publico_atend']);
        $stmt->bindValue(':modalidade', $data['modalidade']);
        $stmt->bindValue(':acomp_terapeutico', $data['acomp_terapeutico']);
        $stmt->bindValue(':mini_curr', $data['mini_curr']);
        $stmt->bindValue(':tipo_ass', $data['tipo_ass']);
        $stmt->bindValue(':crp_crm', $data['crp_crm']);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        // Bind da foto se existir
        if (isset($data['foto'])) {
            $stmt->bindValue(':foto', $data['foto']);
        }

        return $stmt->execute();
    }

    public function updatePhoto($id, $fileName)
    {
        $sql = "UPDATE {$this->table} SET foto = :foto WHERE id_associados = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':foto', $fileName);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updatePassword($id, $newPasswordHash)
    {
        $sql = "UPDATE {$this->table} SET senha = :senha WHERE id_associados = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':senha', $newPasswordHash);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Busca pública para a página "Encontre um Profissional"
     */
    public function getPublicList($search = '')
    {
        $sql = "SELECT nomever, foto, cidade_at, publico_atend, mini_curr, rede_social 
                FROM {$this->table} 
                WHERE id_status = 1"; // Apenas ativos

        if (!empty($search)) {
            $sql .= " AND (nomever LIKE :search OR cidade_at LIKE :search OR publico_atend LIKE :search)";
        }

        $sql .= " ORDER BY RAND()"; // Aleatório para não privilegiar ninguém

        $stmt = $this->db->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%{$search}%");
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Métodos para recuperação de senha (Token)
    public function getUserByToken($email, $token)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email AND reset_token = :token AND reset_token_expires > NOW() LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePasswordAndClearToken($id, $hash)
    {
        $sql = "UPDATE {$this->table} SET senha = :senha, reset_token = NULL, reset_token_expires = NULL WHERE id_associados = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':senha', $hash);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    // =================================================================
    // ÁREA DE RECUPERAÇÃO DE SENHA
    // =================================================================

    public function setResetToken($email, $token, $expires)
    {
        $sql = "UPDATE {$this->table} SET reset_token = :token, reset_token_expires = :expires WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':expires', $expires);
        $stmt->bindValue(':email', $email);
        return $stmt->execute();
    }

    // =================================================================
    // ÁREA DE PESQUISA PÚBLICA (USADO NO SITE CONTROLLER)
    // =================================================================

    /**
     * Retorna a lista de profissionais ativos para a página de busca
     */


    // --- MÉTODOS ADMINISTRATIVOS ---

    /**
     * Alterna status (1 = ativo, 0 = inativo)
     */
    public function toggleStatus($id, $currentStatus)
    {
        $newStatus = ($currentStatus == 1) ? 0 : 1;
        $sql = "UPDATE {$this->table} SET id_status = :status WHERE id_associados = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', $newStatus, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id_associados = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
