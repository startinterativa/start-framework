<?php
    namespace StartInterativa\StartFramework\Controller;

    class Logs extends \StartInterativa\StartFramework\Base\Controller {

        function __construct() {
            parent::__construct();
            $this->defaultAction = "processListLogs";
            $this->methods = ["lista"=>"processListLogs"];
        }

        public function list() {
            $this->page = 'logs/lista';
            $this->helper->isAllowedUser(array('admin'));

            $this->data['logs'] = $GLOBALS['db']['orm']->getRepository('StartInterativa\StartFramework\Model\ORM\StartLog')->findBy(array(), array('datetime' => 'DESC'), 100);;
        }
        
    }

 ?>
