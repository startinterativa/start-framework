<?php
    namespace StartInterativa\StartFramework\Core;
    class Login extends \StartInterativa\StartFramework\Base\Controller {

        function __construct() {
            parent::__construct();
            $this->action = "processLogin";
            $this->methods = ["login"=>"processLogin"];
        }

        public function login() {
            if(!self::isLogged()) { //NÃO TA LOGADO
                if (isset($_GET['route']) AND $_GET['route'] =='login') { //CHAMANDO O MODEL PARA LOGAR
                    self::initSession();
                } else { //FAZENDO LOGIN NA VIEW
                    self::processLogin();
                    $this->helper->renderPage($this->page, $this->data);
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
            $this->data['footer']['dontShowFooter'] = true;
        }
    }

 ?>
