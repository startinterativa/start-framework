<?php
    namespace StartInterativa\StartFramework\Core;
    class Database {

        private function __construct () {

        }

        public static function conexao ($db) {
            try {
                $db = new \PDO("mysql:host=".$db['server'].";dbname=".$db['dbname'].";charset=utf8mb4", $db['user'], $db['password']) or die("Não foi possível conectar com o servidor de dados!");
            } catch (exception $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
            return $db;
        }
        
        public static function orm($db) {
            $isDevMode = false;
            
            if(isset($GLOBALS['start']['config']->localConfig['env']) && $GLOBALS['start']['config']->localConfig['env'] == 'dev') {
                $isDevMode = true;
            }
            
            $entities = array("Model/ORM", "vendor/startinterativa/start-php-framework/Classes/Model/ORM");
            $db['driver'] = 'pdo_mysql';
            $db['charset'] = 'utf8mb4';
            
            $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($entities, $isDevMode);
            $entityManager = \Doctrine\ORM\EntityManager::create($db, $config);

            return $entityManager;
        }
    }

 ?>
