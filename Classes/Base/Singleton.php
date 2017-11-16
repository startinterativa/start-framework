<?php
    namespace StartInterativa\StartFramework\Base;

    class Singleton {

        public static $instances = array();

        private function __construct() {
        }

        private function __clone() {
        }

        private function __wakeup() {
        }

        public static function getInstance() {
            $class = get_called_class();
            if (!isset(self::$instances[$class])) {
                self::$instances[$class] = new $class();
            }

            return self::$instances[$class];
        }
        
        public function getInstanceOf($class) {
            return self::$instances[$class];
        }
        
    }


?>
