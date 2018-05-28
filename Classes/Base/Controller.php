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
        var $params;

        function __construct() {
            $this->helper = \StartInterativa\StartFramework\Support\Helper::getInstance();
            $daoClasses = $GLOBALS['start']['config']->frameworkConfig['Classes']['DAO'];
            $this->type = 'html';
            $this->header = $GLOBALS['start']['config']->frameworkConfig['template']['defaultHeader'];
            $this->footer = $GLOBALS['start']['config']->frameworkConfig['template']['defaultFooter'];
            $this->dao = array();
            $this->processBasicData();

            if(isset($daoClasses)) {
                foreach ($daoClasses as $dao => $namespace) {
                    $this->dao[$dao] = $namespace::getInstance();
                }
            }
        }

        public function process($method) {

            $this->configure();
            
            if(!empty($method)) {
                if(isset($this->methods[$method])) {
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
            
            if (isset($GLOBALS['start']['config']->frameworkConfig['notification']) && $GLOBALS['start']['config']->frameworkConfig['notification']) {
                $this->addScript('vendor/startinterativa/start-framework/Core/Notification/notification.js', "Notifications");
            }
            
        }

        public function render() {
            $this->helper->renderPage($this);
        }

        private function processBasicData() {
            $data = array();
            $data['header'] = array();
            $data['body'] = array();
            $data['footer'] = array();

            if(isset($_COOKIE['alert']) && $_COOKIE['alert'] != '') {
                $data['header']['alert'] = unserialize($_COOKIE['alert']);
                unset($_COOKIE['alert']);
                setcookie('alert', '', time()-3600, '/');
            }

            $data['header']['base'] = $this->helper->getServerProtocol() . $_SERVER['SERVER_NAME'];
            
            if (class_exists('\\Controller\\SpecificController')) {
                $specificController = new \Controller\SpecificController();
                $specificController->helper = $this->helper;
                $specificController->processBasicData($data);
            }
            
            $data['footer']['version'] = $this->helper->getProjectVersion();
            
            if (!isset($_SESSION['login'])) {
                $this->data = $data;
                return;
            }
            
            $data['header']['login'] = $_SESSION['login'];
            
            // Adiciona o tipo de login ao data body
            if (isset($_SESSION['login'])) {
                $this->data['body']['login']['type'] = $_SESSION['login']['type'];
            }
            

            $this->data = $data;
        }

        private function getConfigProvider() {
            $data['id'] = isset($_GET['id']) ? $_GET['id'] : null;

            if(isset($_SESSION['login']['type']) && !$this->helper->isNotUser()) {
                $data['id'] = $_SESSION['login']['id'];
            }

            $data['postagem'] = isset($_GET['postagem']) ? $_GET['postagem'] : null;
            $data['filtro'] = isset($_GET['filtro']) ? $_GET['filtro'] : null;
            $data['periodo'] = isset($_GET['periodo']) ? $_GET['periodo'] : null;
            $data['limit'] = isset($_GET['limit']) ? $_GET['limit'] : null;

            $data['group'] = null;
            return $data;
        }

        public function alert($tipo, $titulo, $texto) {
            if($tipo == true) $tipo = 'success';
            if($tipo == false) $tipo = 'error';
            $this->data['header']['alert']['tipo'] = $tipo;
            $this->data['header']['alert']['titulo'] = $titulo;
            $this->data['header']['alert']['texto'] = $texto;
        }

        public function addScript($path, $comment = false) {
            $this->data['footer']['scripts'][] = array("path" => $path, "comment" => $comment);
        }

        public function addCSS($path, $comment = false) {
            $this->data['header']['css'][] = array("path" => $path, "comment" => $comment);
        }
        
        private function configure() {
            if(is_array($this->params)) {
                foreach ($this->params as $param) {
                    $this->config[$param] = null;
                    if(isset($_GET[$param])) {
                        $this->config[$param] = $_GET[$param];
                    }
                }
            }
        }

    }
?>
