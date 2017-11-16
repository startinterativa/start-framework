<?php
    namespace StartInterativa\StartFramework\Base;

    class Singleton   {

        public static $instances = array();
        var $helper;

        private function __construct() {
        }

        private function __clone() {
        }

        private function __wakeup() {
        }

        public static function getInstance($helper) {
            $class = get_called_class();
            if (!isset(self::$instances[$class])) {
                self::$instances[$class] = new $class();
                self::$instances[$class]->helper = $helper;
            }

            return self::$instances[$class];
        }
        
        public function getInstanceOf($class) {
            return self::$instances[$class];
        }
        
    }


?>
