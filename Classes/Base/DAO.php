<?php
    namespace StartInterativa\StartFramework\Base;

    class DAO extends \StartInterativa\StartFramework\Base\Singleton {
        
        var $helper;
        
        public static function getInstance() {
            $class = parent::getInstance();
            $class->helper = \StartInterativa\StartFramework\Support\Helper::getInstance();
            return $class;
        }

    }
?>