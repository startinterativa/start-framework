<?php
    namespace StartInterativa\StartFramework\Core\Configuration;

    class Init {
        
        var $frameworkConfig;
        var $localConfig;
        
        public function __construct() {
            $frameworkConfig = file_get_contents(SITEROOT.'/FrameworkConfig.json');
            $this->frameworkConfig = json_decode($frameworkConfig, true);
            
            $localConfig = file_get_contents(SITEROOT.'/LocalConfig.json');
            $this->localConfig = json_decode($localConfig, true);
        }
        
            
        public function config() {
            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            $GLOBALS['db']['conexao'] = \StartInterativa\StartFramework\Core\Database::conexao($this->localConfig['db']);
        }
        
        public function execute() {
            if(isset($this->frameworkConfig['loginRequired']) && $this->frameworkConfig['loginRequired'] ==  true) {
                $login = new \StartInterativa\StartFramework\Core\Login();
                $success = $login->login();
                if($success) {
                    \StartInterativa\StartFramework\Core\Route::route();
                }
            }
            
        }
        
    }
?>