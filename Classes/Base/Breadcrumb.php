<?php 
    namespace StartInterativa\StartFramework\Base;
    
    class Breadcrumb {
        var $title;
        var $link;
        
        public function __construct($title, $link) {
            $this->title = $title;
            $this->link = $link;
        }
    }

 ?>