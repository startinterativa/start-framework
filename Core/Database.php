<?php
    namespace StartInterativa\StartFramework\Core;

    use \Doctrine\ORM\Tools\Setup;
    use \Doctrine\ORM\EntityManager;
    use \Doctrine\Common\Proxy\AbstractProxyFactory;
    
    class Database {

        private function __construct () {

        }

        public static function connect($db) {
            try {
                $db = new \PDO("mysql:host=".$db['host'].";dbname=".$db['dbname'].";charset=utf8mb4", $db['user'], $db['password']) or die("Não foi possível conectar com o servidor de dados!");
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
            
            $config = Setup::createAnnotationMetadataConfiguration($entities, $isDevMode);
	    $config->setAutoGenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS);
            $entityManager = EntityManager::create($db, $config);

            return $entityManager;
        }
    }

 ?>
