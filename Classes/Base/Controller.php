<?php
    namespace StartInterativa\StartFramework\Base;

    class Controller {
        
        var $helper;
        var $methods;
        var $dao;
        var $data;
        var $action;
        var $config;
        var $type;
        var $header;
        var $footer;
        var $page;
        var $parameters;

        function __construct() {
            $this->helper = \StartInterativa\StartFramework\Support\Helper::getInstance();
            $this->type = 'html';
            $this->header = $GLOBALS['start']['config']->frameworkConfig['template']['defaultHeader'];
            $this->footer = $GLOBALS['start']['config']->frameworkConfig['template']['defaultFooter'];
            $this->dao = array();
            $this->processBasicData();

            // Deprecated 
            if(isset($GLOBALS['start']['config']->frameworkConfig['Classes']['DAO'])) {
                foreach ($GLOBALS['start']['config']->frameworkConfig['Classes']['DAO'] as $dao => $namespace) {
                    $this->dao[$dao] = $namespace::getInstance();
                }
            }
        }

        public function process($method) {
            
            if(!empty($method)) {
                if(method_exists($this, $method)) {
                    $this->action = $method;
                } else if(isset($this->methods[$method])) {
                    $this->action = $this->methods[$method];
                } else {
                    $this->helper->redirect404();
                }
            } else {
                $this->action = $this->defaultAction;
            }

            call_user_func(array($this, $this->action), 1);
            
            if (method_exists($this, 'processBreadcrumbs')) {
                $this->processBreadcrumbs();
            }
            
            $this->addScript('vendor/startinterativa/start-php-framework/Core/Frontend/start_framework.js', "StartFramework");
            
            if (isset($GLOBALS['start']['config']->frameworkConfig['notification']) && $GLOBALS['start']['config']->frameworkConfig['notification']) {
                $this->addScript('vendor/startinterativa/start-framework/Core/Notification/notification.js', "Notifications");
            }
            
        }

        public function render() {
            $this->helper->renderPage($this);
        }

        private function processBasicData() {
            $this->data = array();

            if(isset($_COOKIE['alert']) && $_COOKIE['alert'] != '' && !($this instanceof \StartInterativa\StartFramework\Core\Login)) {
                $this->data['alert'] = unserialize($_COOKIE['alert']);
                unset($_COOKIE['alert']);
                setcookie('alert', '', time()-3600, '/');
            }

            if(isset($GLOBALS['start']['config']->localConfig['project_name'])) {
                $this->data['title'] = $GLOBALS['start']['config']->localConfig['project_name'];
            }

            $this->data['base'] = $this->helper->getBaseUrl();
            
            if (class_exists('\\Controller\\SpecificController')) {
                $specificController = new \Controller\SpecificController();
                $specificController->helper = $this->helper;
                $specificController->processBasicData($this->data);
            }
            
            $this->data['version'] = $this->helper->getProjectVersion();
            
            if (!isset($_SESSION['login'])) {
                return;
            }
            
            $this->data['login'] = $_SESSION['login'];
            
            // Adiciona o tipo de login ao data body
            if (isset($_SESSION['login'])) {
                $this->data['login']['type'] = $_SESSION['login']['type'];
            }
        }

        public function alert($tipo, $titulo, $texto) {
            if($tipo === true) $tipo = 'success';
            if($tipo === false) $tipo = 'error';
            $this->data['alert']['tipo'] = $tipo;
            $this->data['alert']['titulo'] = $titulo;
            $this->data['alert']['texto'] = $texto;
        }

        // Deprecated
        public function addScript($path, $comment = false) {
            $this->data['scripts'][] = array("path" => $path, "comment" => $comment);
        }

        // Deprecated
        public function addCSS($path, $comment = false) {
            $this->data['css'][] = array("path" => $path, "comment" => $comment);
        }

    }
?>
