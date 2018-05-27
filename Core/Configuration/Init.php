<?php
    namespace StartInterativa\StartFramework\Core\Configuration;

    class Init {
        
        var $frameworkConfig;
        var $localConfig;
        
        public function __construct() {
            if(!defined('SITEROOT')) {
                define('SITEROOT', dirname($_SERVER["SCRIPT_FILENAME"]));
            }
            $frameworkConfig = file_get_contents(SITEROOT.'/FrameworkConfig.json');
            $this->frameworkConfig = json_decode($frameworkConfig, true);
            
            $localConfig = file_get_contents(SITEROOT.'/LocalConfig.json');
            $this->localConfig = json_decode($localConfig, true);
        }
        
            
        public function config() {
            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            $GLOBALS['db']['conexao'] = \StartInterativa\StartFramework\Core\Database::conexao($this->localConfig['db']);
            
            $GLOBALS['db']['orm'] = \StartInterativa\StartFramework\Core\Database::orm($this->localConfig['db']);;
            
            header('Content-Type: text/html; charset=utf-8');
            
            if(isset($GLOBALS['start']['config']->localConfig['env']) && $this->localConfig['env'] == 'dev') {
                ini_set('display_errors', 1);
            }
            
            if(isset($this->localConfig['timezone'])) {
                date_default_timezone_set($this->localConfig['timezone']);
            }
        }
        
        public function execute() {
            if(isset($this->frameworkConfig['loginRequired']) && $this->frameworkConfig['loginRequired'] ==  true) {
                session_start();
                ob_start();
                $login = new \StartInterativa\StartFramework\Core\Login();
                $success = $login->login();
            }
            if($success || !$this->frameworkConfig['loginRequired']) {
                \StartInterativa\StartFramework\Core\Route::route();
            }
            
        }
        
    }
?>