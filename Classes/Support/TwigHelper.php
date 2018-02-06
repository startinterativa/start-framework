<?php
    namespace vitormarcelino\StartFramework\Support;

    class TwigHelper extends \Twig_Extension {

        var $environment;
    	var $helper;

        public function __construct(\Twig_Environment $env, $helper)
        {
        	$this->environment = $env;
        	$this->helper = $helper;
        }

        public function getUrl($params = null) {
        	return $this->helper->getUrl($params);
        }

        /*
            $type [date, time, datetime]
        */
        public function getDate($timestamp, $type = 'date') {
            return $this->helper->getDate($timestamp, $type);
        }

        public function getThumb($img){
            $explodedImg = explode(",", $img);
            
            if(is_array($explodedImg)) {
                $explodedPath = explode("/", $explodedImg[0]);
                array_splice($explodedPath, count($explodedPath)-1, 0, array('thumbs'));
                $thumb = implode("/", $explodedPath);

                if(!is_file($thumb)) {
                    $image = new \Eventviva\ImageResize($explodedImg[0]);
                    $image->resizeToWidth(300);
                    $image->save($thumb);
                }
                
                return $thumb;
            }
            
        }
        
        public function getImage($img) {
            $explodedImg = explode(",", $img);
            $html = '';
            
            if(is_array($explodedImg)) {
                if(count($explodedImg) > 1) {
                    $html = $this->helper->processHTML('components/carousel', array('imagens' => $explodedImg));
                } else {
                    $html = $this->helper->processHTML('components/imagem', array('imagem' => $explodedImg[0]));
                }
            }
            return $html;
        }

        public function getUsername() {
            return $_SESSION['login']['usuario'];
        }

        public function getUserPicture() {
            return $_SESSION['login']['imagem'];
        }
        
        public function format_real($val) {
            return $this->helper->getMoneyFormat($val);
        }

        public function getFunctions()
        {
        	return array(
    	        new \Twig_SimpleFunction('getUrl', array($this, 'getUrl'), array('needs_context' => false)),
                new \Twig_SimpleFunction('getDate', array($this, 'getDate'), array('needs_context' => false)),
                new \Twig_SimpleFunction('getThumb', array($this, 'getThumb'), array('needs_context' => false)),
                new \Twig_SimpleFunction('getImage', array($this, 'getImage'), array('needs_context' => false, 'pre_escape' => 'html', 'is_safe' => array('html'))),
                new \Twig_SimpleFunction('getUsername', array($this, 'getUsername'), array('needs_context' => false)),
                new \Twig_SimpleFunction('getUserPicture', array($this, 'getUserPicture'), array('needs_context' => false)),
                new \Twig_SimpleFunction('format_real', array($this, 'format_real'), array('needs_context' => false))
    	    );
        }


    }

?>
