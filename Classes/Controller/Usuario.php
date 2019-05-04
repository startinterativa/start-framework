<?php
    namespace StartInterativa\StartFramework\Controller;

    class Usuario extends \StartInterativa\StartFramework\Base\Controller {

        var $hookClass = false;

        function __construct() {
            parent::__construct();

            if(class_exists('\Hooks\StartUser')) {
                $this->hookClass = new \Hooks\StartUser();
            }
        }

        public function new() {
            $this->page = 'usuario/form';
            $this->helper->isAllowedUser(array('admin'));
            
            if(is_array($GLOBALS['start']['config']->frameworkConfig['loginTypes'])) {
                $this->data['body']['loginTypes'] = $GLOBALS['start']['config']->frameworkConfig['loginTypes'];
            }

            if($this->hookClass) {
                $this->hookClass->process($this);
            }
            
            if(isset($_POST['action'])) {
                if($_POST['password'] != $_POST['password_confirm']) {
                    die("A senha precisa ser igual");
                } else {
                    $user = new \StartInterativa\StartFramework\Model\ORM\StartUser();
                    $user->username = $_POST['username'];
                    $user->password = crypt($_POST['password'], '');
                    $user->type = $_POST['type'];
                    $user->email = $_POST['email'];
                    $user->image = $_POST['pathImagem'];
                    $user->crdate = time();
                    $user->config = "{}";

                    $GLOBALS['db']['orm']->persist($user);
                    $GLOBALS['db']['orm']->flush();

                    if($this->hookClass) {
                        $this->hookClass->postNewUser($user);
                    }

                    $this->helper->redirect('usuarios');
                }
            }

        }

        public function list() {
            $this->page = 'usuario/list';
            $this->helper->isAllowedUser(array('admin'));
            
            $this->data['body']['users'] = $GLOBALS['db']['orm']->getRepository('StartInterativa\StartFramework\Model\ORM\StartUser')->findAll();

        }
        
        public function edit() {
            $this->page = 'usuario/form';
            $this->helper->isAllowedUser(array('admin'));

            if(is_array($GLOBALS['start']['config']->frameworkConfig['loginTypes'])) {
                $this->data['body']['loginTypes'] = $GLOBALS['start']['config']->frameworkConfig['loginTypes'];
            }

            if($this->hookClass) {
                $this->hookClass->process($this);
            }
            
            $user = $GLOBALS['db']['orm']->getRepository('StartInterativa\StartFramework\Model\ORM\StartUser')->get($this->parameters['id']);

            if(isset($_POST['action']) && $_POST['action'] == 'update') {
                
                $user->username = $_POST['username'];
                
                if(!empty($_POST['password']) && ($_POST['password'] == $_POST['password_confirm'])){
                    $user->password = crypt($_POST['password'], '');
                }
                
                $user->type = $_POST['type'];
                $user->email = $_POST['email'];
                $user->image = $_POST['pathImagem'];
                
                $GLOBALS['db']['orm']->merge($user);
                $GLOBALS['db']['orm']->flush();

                if($this->hookClass) {
                    $this->hookClass->postUpdateUser($user);
                }

                $this->helper->redirect('usuarios');
            }
            
            $this->data['body']['user'] = $user;
        }
}

 ?>
