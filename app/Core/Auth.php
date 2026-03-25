<?php

namespace App\Core;

class Auth
{
    /**
     * Inicia a sessão se ainda não estiver iniciada
     */
    public static function init()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Verifica se o usuário está logado
     * @return bool
     */
    public static function check()
    {
        self::init();
        // Verifica se existe o ID do usuário na sessão
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Retorna o ID do usuário logado
     */
    public static function id()
    {
        self::init();
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Retorna o NÍVEL (Role) do usuário logado
     * Útil para verificações como: if (Auth::level() > 2)
     */
    public static function level()
    {
        self::init();
        // Retorna o valor inteiro da role, ou 0 se não estiver definido
        return isset($_SESSION['user_role']) ? (int)$_SESSION['user_role'] : 0;
    }

    /**
     * Retorna todos os dados do usuário na sessão
     */
    public static function user()
    {
        self::init();
        return self::check() ? $_SESSION : null;
    }

    /**
     * Realiza o login (salva na sessão)
     * Aceita array ou parâmetros individuais para compatibilidade
     */
    public static function login($idOrData, $name = null, $email = null, $role = 0, $foto = null)
    {
        self::init();

        if (is_array($idOrData)) {
            // Se passar um array
            $_SESSION['user_id']    = $idOrData['id'] ?? null;
            $_SESSION['user_name']  = $idOrData['name'] ?? null;
            $_SESSION['user_email'] = $idOrData['email'] ?? null;
            $_SESSION['user_role']  = $idOrData['user_tipo'] ?? 0; // Nível de acesso
            $_SESSION['user_foto']  = $idOrData['photo'] ?? null;
        } else {
            // Se passar parâmetros individuais (legado)
            $_SESSION['user_id']    = $idOrData;
            $_SESSION['user_name']  = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role']  = $role;
            $_SESSION['user_foto']  = $foto;
        }
        
        // Regenera o ID da sessão por segurança
        session_regenerate_id(true);
    }

    /**
     * Realiza o logout
     */
    public static function logout()
    {
        self::init();
        session_unset();
        session_destroy();
    }
}