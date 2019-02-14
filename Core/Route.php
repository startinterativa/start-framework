<?php
    namespace StartInterativa\StartFramework\Core;

    use Symfony\Component\Routing\Matcher\UrlMatcher;
    use Symfony\Component\Routing\RequestContext;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Generator\UrlGenerator;
    use Symfony\Component\Config\FileLocator;
    use Symfony\Component\Routing\Loader\YamlFileLoader;
    use Symfony\Component\Routing\Exception\ResourceNotFoundException;
    
    class Route {
        
        public static function route() {

            try {
                // Load routes from the yaml file
                $fileLocator = new FileLocator(array(__DIR__));
                $loader = new YamlFileLoader($fileLocator);
                $routes = $loader->load(SITEROOT . '/routes.yaml');
                $frameworkRoutes = $loader->load(SITEROOT . '/vendor/startinterativa/start-php-framework/routes.yaml');
                
                $routes->addCollection($frameworkRoutes);

                // Init RequestContext object
                $context = new RequestContext();
                $context->fromRequest(Request::createFromGlobals());

                // Init UrlMatcher object
                $matcher = new UrlMatcher($routes, $context);

                // Find the current route
                $parameters = $matcher->match($context->getPathInfo());
                
                if(isset($parameters['controller'])) {
                    $c = explode("::", $parameters['controller']);
                    $class = $c[0];
                    $method = $c[1];
                }

                if(class_exists($class)) {
                    $controller = new $class();
                } else {
                    \StartInterativa\StartFramework\Support\Helper::getInstance()->redirect404();
                }
                $controller->parameters = $parameters;
                $controller->process($method);
                $controller->render();
            
            }
            catch (ResourceNotFoundException $e) {
                if($GLOBALS['start']['config']->localConfig['env'] == 'prod') {
                    \StartInterativa\StartFramework\Support\Helper::getInstance()->redirect404();
                }
                echo $e->getMessage();
            }
        }
        
    }
