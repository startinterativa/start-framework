<?php
    namespace StartInterativa\StartFramework\Base;

    class Controller {
        
        var $helper;
        var $methods;
        var $dao;
        var $data;
        var $defaultAction;
        var $page;

        function __construct() {
            $this->helper = \StartInterativa\StartFramework\Support\Helper::getInstance();
            $daoClasses = $GLOBALS['start']['config']->frameworkConfig['Classes']['DAO'];
            $this->dao = array();
            if(isset($daoClasses)) {
                foreach ($daoClasses as $dao => $namespace) {
                    $this->dao[$dao] = $namespace::getInstance();
                }
            }
        }

        public function process($method) {
            if (isset($_SESSION['login'])) {
                $this->data['header']['login']['tipo'] = $_SESSION['login']['tipo'];
            }

            self::processBasicData();
            
            $action = $this->defaultAction;
            if(!empty($method)) {
                if(isset($this->methods[$method])) {
                    $action = $this->methods[$method];
                } else {
                    $this->helper->redirect404();
                }
            }

            call_user_func(array($this, $action), 1);
        }

        public function render() {
            $this->helper->renderPage($this->page, $this->data);
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

            if (!isset($_SESSION['login'])) {
                return $data;
            }
            $data['body']['login']['tipo'] = $_SESSION['login']['tipo'];
            $data['header']['login']['tipo'] = $_SESSION['login']['tipo'];
            $data['header']['login']['empresa'] = $_SESSION['login']['empresa'];
            $data['header']['login']['usuario'] = $_SESSION['login']['usuario'];
            $data['header']['login']['imagem'] = $_SESSION['login']['imagem'];
            $data['header']['login']['id'] = $_SESSION['login']['id'];

            $data['header']['base'] = $this->helper->getServerProtocol() . $_SERVER['SERVER_NAME'];


            if($this->helper->isAllowedUser(array('admin','designer','redator'), false)) {
                $data['header']['clientes'] = \Model\DAO\Cliente::getAllClientes();
            }

            if($this->helper->isAllowedUser(array('admin','redator'), false)) {
                $data['header']['revisao']['count'] = $this->dao['postagem']->count(array('status' => '0', 'single' => true));
            }

            if($_SESSION['login']['tipo'] == 'cliente') {
                $configBasicData['id'] = $_SESSION['login']['id'];
                $configBasicData['group'] = 'mes_ano';
                $data['header']['sidebar']['planejameto'] = $this->planejamentoDAO->getResult($configBasicData);
            }

            $data['footer']['version'] = $this->helper->getProjectVersion();
            $this->data = $data;
        }

        private function getConfigProvider() {
            $data['id'] = isset($_GET['id']) ? $_GET['id'] : null;

            if(isset($_SESSION['login']['tipo']) && !$this->helper->isNotUser()) {
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

    }
?>
