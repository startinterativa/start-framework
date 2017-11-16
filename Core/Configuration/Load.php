<?php
    namespace StartInterativa\StartFramework\Core\Configuration;

    class Load {
        
        public static function getConfig() {
            $mapFile = file_get_contents(SITEROOT.'/config.json');
            return json_decode($mapFile, true);
        }
        
    }
?>