<?php
    namespace StartInterativa\StartFramework\Controller;

    class Usuario extends \StartInterativa\StartFramework\Base\Controller {

        function __construct() {
            parent::__construct();
            $this->defaultAction = "processListUsuarios";
            $this->methods = ["novo"=>"processNewUsuario", "lista"=>"processListUsuarios"];
        }

        public function processNewUsuario() {
            $this->page = 'usuario/form';
            $this->helper->isAllowedUser(array('admin'));
            
            
            if(is_array($GLOBALS['start']['config']->frameworkConfig['loginTypes'])) {
                $this->data['body']['loginTypes'] = $GLOBALS['start']['config']->frameworkConfig['loginTypes'];
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
                    $user->image = $_POST['image'];
                    $user->crdate = time();

                    $GLOBALS['db']['orm']->persist($user);
                    $GLOBALS['db']['orm']->flush();
                    
                    $this->helper->redirect('usuario,lista');
                }
            }

        }

        public function processListUsuarios() {
            $this->page = 'usuario/list';
            $this->helper->isAllowedUser(array('admin'));
            
            $this->data['body']['users'] = $GLOBALS['db']['orm']->getRepository('StartInterativa\StartFramework\Model\ORM\StartUser')->findAll();

        }
}

 ?>
