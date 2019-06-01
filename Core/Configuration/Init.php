<?php
    namespace StartInterativa\StartFramework\Core\Configuration;

    use \StartInterativa\StartFramework\Core\Database;
    use \StartInterativa\StartFramework\Core\Route;
    use \StartInterativa\StartFramework\Core\Login;

    class Init {
        
        var $frameworkConfig;
        var $localConfig;
        var $project_version;
        
        public function __construct() {
            if(!defined('SITEROOT')) {
                define('SITEROOT', dirname($_SERVER["SCRIPT_FILENAME"]));
            }
            $frameworkConfig = file_get_contents(SITEROOT.'/FrameworkConfig.json');
            $this->frameworkConfig = json_decode($frameworkConfig, true);
            
            $localConfig = file_get_contents(SITEROOT.'/LocalConfig.json');
            $this->localConfig = json_decode($localConfig, true);

            $composer = json_decode(file_get_contents(SITEROOT.'/composer.json'), true);
            $this->project_version = $composer['version'];
        }
        
            
        public function config() {
            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            $GLOBALS['db']['conexao'] = Database::connect($this->localConfig['db']);
            
            $GLOBALS['db']['orm'] = Database::orm($this->localConfig['db']);;
            
            header('Content-Type: text/html; charset=utf-8');
            
            if(isset($GLOBALS['start']['config']->localConfig['env']) && $this->localConfig['env'] == 'dev') {
                error_reporting(-1);
                ini_set("display_errsors", "1");
                ini_set("log_errors", 1);
                ini_set("error_log", SITEROOT . "/php-error.log");
            }
            
            if(isset($GLOBALS['start']['config']->localConfig['env']) && $this->localConfig['env'] == 'prod' && isset($GLOBALS['start']['config']->localConfig['sentry_url'])) {
                $client = new \Raven_Client($GLOBALS['start']['config']->localConfig['sentry_url']);
                $client->user_context($this->getSentryContext());

                $error_handler = new \Raven_ErrorHandler($client);
                $error_handler->registerExceptionHandler();
                $error_handler->registerErrorHandler();
                $error_handler->registerShutdownFunction();
            }
            
            if(isset($this->localConfig['timezone'])) {
                date_default_timezone_set($this->localConfig['timezone']);
            }

            if(isset($this->frameworkConfig['loginRequired']) && $this->frameworkConfig['loginRequired'] ==  true) {
                session_start();
                ob_start();
            }
        }

        private function getSentryContext() {
            $context = array();

            if ($GLOBALS['start']['config']->localConfig['project_name']) {
                $context['project_name'] = $GLOBALS['start']['config']->localConfig['project_name'];
            }

            if($this->project_version) {
                $context['version'] = $this->project_version;
            }

            return $context;
        }
        
        public function execute() {
            if(isset($this->frameworkConfig['loginRequired']) && $this->frameworkConfig['loginRequired'] ==  true) {
                $login = new Login();
                $success = $login->login();
            }
            if($success || !$this->frameworkConfig['loginRequired']) {
                Route::route();
            }
            
        }
        
    }
?>