<?php 
    namespace vitormarcelino\StartFramework\Base;
    
    class Breadcrumb {
        var $title;
        var $link;
        
        public function __construct($title, $link = null) {
            $this->title = $title;
            $this->link = $link;
        }
    }

 ?>