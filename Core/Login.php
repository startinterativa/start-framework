<?php
    namespace StartInterativa\StartFramework\Core;
    class Login extends \StartInterativa\StartFramework\Base\Controller {
        
        var $requestUrl;

        function __construct() {
            parent::__construct();
            $this->action = "processLogin";
            $this->methods = ["login"=>"processLogin"];
            $this->header = $GLOBALS['start']['config']->frameworkConfig['template']['loginHeader'];
            $this->page = $GLOBALS['start']['config']->frameworkConfig['template']['loginPage'];
            $this->footer = $GLOBALS['start']['config']->frameworkConfig['template']['loginFooter'];
        }

        public function login() {
            if(!$this->isLogged()) { //NÃO TA LOGADO
                $this->requestUrl = $this->helper->getCurrentUrl();
                if (isset($_POST['usuario']) AND isset($_POST['senha'])) { //CHAMANDO O MODEL PARA LOGAR
                    $this->initSession();
                } else { //FAZENDO LOGIN NA VIEW
                    $this->processLogin();
                    $this->helper->renderPage($this);
                    return false;
                }
            }
            return true;
        }

        public function isLogged() {
            if (!isset($_SESSION['login']['status'])) {
                return false;
            }
            return true;
        }

        private function initSession() {
            $usuario = new \Model\Object\Usuario($_POST['usuario'], $_POST['senha']);
            $queryString = null;
            if($this->dao['usuario']->login($usuario)) {
                $_SESSION['login']['status'] = "1";
            } else {
                $queryString = 'loginIncorreto';
            }
            if($this->requestUrl) {
                $queryString = $this->requestUrl;
            }
            $this->helper->redirect($queryString);
        }

        public function processLogin() {
            $this->page = 'general/login';
            $this->data['header']['title'] = "Start Post - Login";
            $this->data['header']['base'] = $this->helper->getServerProtocol() . $_SERVER['SERVER_NAME'];
            $this->data['body'] = array();
            if (isset($_GET['route']) AND $_GET['route'] == 'loginIncorreto') {
                $this->data['body']['alert']['tipo'] = 'danger';
                $this->data['body']['alert']['titulo'] = 'Acesso não permitido!';
                $this->data['body']['alert']['texto'] = 'Login Incorreto';
            }
        }
    }

 ?>
