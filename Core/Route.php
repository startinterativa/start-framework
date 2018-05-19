<?php
    namespace StartInterativa\StartFramework\Core;
    class Route {
        public static function route() {
            $class = "\Controller\Dashboard";
            if(isset($_GET['route'])) {
                $route = $_GET['route'];
                
                if($route == 'sair') {
                    session_unset();
                    ob_end_clean();
                    \StartInterativa\StartFramework\Support\Helper::getInstance()->redirect();
                    exit();
                }
                
                if($route == 'login') {
                    $class = '\StartInterativa\\StartFramework\\Core\\Login';
                    $method = "login";
                } else {
                    $class = '\Controller\\' . ucfirst($_GET['route']);
                    $method = "";
                } 
                
            }
                        
            if(class_exists($class)) {
                $controller = new $class();
            } else {
                \StartInterativa\StartFramework\Support\Helper::getInstance()->redirect404();
            }
            
            if(isset($_GET['method'])) {
                $method = $_GET['method'];
            }
            $controller->process($method);
            $controller->render();
        }
    }

 ?>
