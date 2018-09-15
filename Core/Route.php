<?php
    namespace StartInterativa\StartFramework\Core;

    use Symfony\Component\Yaml\Yaml;
    
    class Route {
        
        public static function route() {
            $class = "\Controller\Dashboard";

            if(!is_file(SITEROOT . '/Routes.yml')) {
                echo "Arquivo de rotas não encontrado.";die;
            }

            $requestRoute = $_SERVER['REQUEST_URI'];
            if(substr($requestRoute, 0, 1) ==  '/') {
                $requestRoute = substr($requestRoute, 1);
            }

            $requestRoute = explode('/', $requestRoute);

            $allRoutes = Yaml::parseFile(SITEROOT . '/Routes.yml');

            $parameters = array();
            $tempArray = $allRoutes;
            // var_dump($allRoutes);die;
            foreach ($requestRoute as $index => $param) {
                if($index == 0) {
                    $parameters['controller'] = $param;
                }
                
                if(isset($tempArray[$param])) {
                    if(is_array($tempArray[$param])) {
                        $tempArray = $tempArray[$param];
                    }
                    echo "Encontrou " . $param . PHP_EOL;
                    continue;
                }

                if(isset($tempArray['params'])) {
                    foreach ($tempArray['params'] as $key => $type) {
                        echo $param . gettype($param) . PHP_EOL;
                        // if(gettype($param) == $type) {
                        //     echo $param . " is " . $type . PHP_EOL;                        
                        // }
                    }
                }

            }
            die;
            // var_dump($parameters);die;

            if(isset($_GET['route'])) {
                $route = $_GET['route'];
                
                if($route == 'sair') {
                    session_unset();
                    ob_end_clean();
                    \StartInterativa\StartFramework\Support\Helper::getInstance()->redirect();
                    exit();
                }
                
                $class = self::getClass($route);
                
            }
            
            if(class_exists($class)) {
                $controller = new $class();
            } else {
                \StartInterativa\StartFramework\Support\Helper::getInstance()->redirect404();
            }
            
            $method = "";
            if(isset($_GET['method'])) {
                $method = $_GET['method'];
            }
            $controller->process($method);
            $controller->render();
        }
        
        private static function getClass($route) {
            $frameworkRoutesClasses = array(
                'usuario' => '\StartInterativa\\StartFramework\\Controller\\Usuario',
                'logs' => '\StartInterativa\\StartFramework\\Controller\\Logs',
            );
            if(isset($frameworkRoutesClasses[$route])) {
                return $frameworkRoutesClasses[$route];
            }
            
            return '\Controller\\' . ucfirst($_GET['route']);
            
        }
    }
