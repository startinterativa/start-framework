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
                
                $result = false;
                if (isset($_POST['usuario']) AND isset($_POST['senha'])) { //CHAMANDO O MODEL PARA LOGAR
                    $result = $this->initSession();
                }

                if(!$result) { //FAZENDO LOGIN NA VIEW
                    $this->processLogin();
                    $this->helper->renderPage($this);
                    return false;
                }
            }
            return true;
        }

        public function logout() {
            session_unset();
            ob_end_clean();
            $this->helper->redirect();
            exit();
        }

        public function isLogged() {
            if (!isset($_SESSION['login']['status'])) {
                return false;
            }
            return true;
        }

        private function initSession() {
            $startUserRepository = $GLOBALS['db']['orm']->getRepository('StartInterativa\StartFramework\Model\ORM\StartUser');
            
            $login = $startUserRepository->login($_POST['usuario'], $_POST['senha']);
            $log = true;

            if($login) {
                $_SESSION['login'] = get_object_vars($login);
                $_SESSION['login']['status'] = 1;
                $message = "Login com sucesso";
                $status = 1;
                $log = !$login->hideLogin;
            } else {
                http_response_code(401);
                $this->alert(false, "Login Incorreto", "Tente novamente");
                $message = "Tentativa de login Incorreto: " . $_POST['usuario'];
                $status = 0;
            }

            if($log) {
                $this->helper->log(
                    array(
                        'type' => 'session',
                        'action' => 'login',
                        'message' => $message,
                        'status' => $status,
                        'tablename' => '',
                        'foreign_id' => 0
                    )
                );
            }

            return boolval($status);
        }

        public function processLogin() {
            $this->page = 'general/login';
            $this->data['header']['title'] = "Start Post - Login";

            $data['header']['base'] = $this->helper->getBaseUrl();

            $this->data['body'] = array();
            if (isset($_GET['route']) AND $_GET['route'] == 'loginIncorreto') {
                $this->data['body']['alert']['tipo'] = 'danger';
                $this->data['body']['alert']['titulo'] = 'Acesso não permitido!';
                $this->data['body']['alert']['texto'] = 'Login Incorreto';
            }
        }
    }

 ?>
