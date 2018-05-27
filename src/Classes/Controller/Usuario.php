<?php
    namespace StartInterativa\StartFramework\Controller;

    class Usuario extends \StartInterativa\StartFramework\Base\Controller {

        function __construct() {
            parent::__construct();
            $this->defaultAction = "processListUsuarios";
            $this->methods = ["novo"=>"processNewUsuario", "lista"=>"processListUsuarios"];
        }

        public function processNewUsuario() {
            $this->helper->isAllowedUser(array('admin'));
            $this->data['body'] = array();

            if(isset($_GET['type']) && $_GET['type']=='cliente') {
                $this->data['body']['clientes'] = $this->dao['cliente']->getAllClientes();
                $this->data['body']['selected'] = $_GET['type'];
            }

            if(isset($_POST['action'])) {
                if($_POST['senha'] != $_POST['confirma']) {
                    die("A senha precisa ser igual");
                } else {
                    $cliente = 0;
                    if(isset($_POST['cliente'])) {
                        $cliente = $_POST['cliente'];
                    }

                    $imagem = $this->helper->getImageObject($_POST['pathImagem']);
                    $usuario = new \Model\Object\Usuario($_POST['usuario'], crypt($_POST['senha'],''), $_POST['tipo'], $cliente, $_POST['email'], $imagem);

                    $res = $this->dao['usuario']->insert($usuario);
                    $this->helper->redirect('usuario,lista');
                }
            }

            $this->page = 'usuario/form';
        }

        public function processListUsuarios() {
            $this->page = 'usuario/list';
            $this->helper->isAllowedUser(array('admin'));
            // $this->data['body']['usuarios'] = $this->dao['usuario']->getAllUsers();
            var_dump($GLOBALS['db']['orm']->getRepository('StartInterativa\StartFramework\Model\ORM\StartUser'));die;
            $test = $GLOBALS['db']['orm']->getRepository('StartInterativa\StartFramework\Model\ORM\StartUser');//->findAll();//findBy(array('status' => 'CLOSED'));

        }
}

 ?>
