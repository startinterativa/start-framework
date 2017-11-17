<?php
    namespace StartInterativa\StartFramework\Core;
    class Route {
        public static function route() {
            
            $route = "inicio";
            if(isset($_GET['route'])) {
                $route = $_GET['route'];
            }
                    
            $method = "";
            if(isset($_GET['method'])) {
                $method = $_GET['method'];
            }
            // var_dump($_GET); die;
            switch ($route) {
                case 'sair':
                    session_unset();
                    ob_end_clean();
                    \StartInterativa\StartFramework\Support\Helper::getInstance()->redirect();
                    break;
                case 'inicio':
                    $controller = new \Controller\Dashboard();
                    break;
                case 'cliente':
                    $controller = new \Controller\Cliente();
                    break;
                case 'postagem':
                    $controller = new \Controller\Postagem();
                    break;
                case 'revisao':
                    $controller = new \Controller\Revisao();
                    break;
                case 'pauta':
                    $controller = new \Controller\Pauta();
                    break;
                case 'usuario':
                    $controller = new \Controller\Usuario();
                    break;
                case 'planejamento':
                    $controller = new \Controller\Planejamento();
                    break;
                case 'callback':
                    $controller = new \Controller\Callback();
                    break;
                default:
                    $controller = new \Controller\Dashboard();
                    break;
            }

            $controller->process($method);
            $controller->render();
        }
    }

 ?>
