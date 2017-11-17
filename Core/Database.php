<?php
    namespace StartInterativa\StartFramework\Core;
    class Database {

        private function __construct () {

        }

        public static function conexao ($db) {
            try {
                $db = new \PDO("mysql:host=".$db['server'].";dbname=".$db['database'].";charset=utf8mb4", $db['user'], $db['password']) or die("Não foi possível conectar com o servidor de dados!");
            } catch (exception $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
            return $db;
        }
    }

 ?>
