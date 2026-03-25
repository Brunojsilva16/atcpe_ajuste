<?php

namespace App\Controllers;

use App\Models\SearchModel;

class SearchController extends BaseController
{
    /**
     * Ponto de entrada para a pesquisa de profissionais via AJAX.
     */
    public function search()
    {
        // Se precisar debugar os campos recebidos, descomente as linhas abaixo:
        // var_dump($_POST); 
        // exit;

        $searchModel = new SearchModel();
        
        /**
         * Mapeamento dos campos do formulário (pesquisa.php) para o Model.
         * Garantimos que todos os nomes de chaves aqui correspondam aos 'name' no HTML.
         */
        $filters = [
            'nome'           => $_POST['nome'] ?? null,
            'publico_atend'  => $_POST['publico_atend'] ?? null,
            'mod_atendimento'=> $_POST['mod_atendimento'] ?? null,
            'plano_saude'    => $_POST['plano_saude'] ?? null,
            'cidade'         => $_POST['cidade'] ?? null,
            'bairro'         => $_POST['bairro'] ?? null,
            'tipo'           => $_POST['tipo_profissional_radio'] ?? 'Todos'
        ];

        // Chama o model passando apenas o array de filtros processado
        $results = $searchModel->searchProfessionals($filters);

        // Limpa qualquer saída acidental e envia o JSON
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }
}