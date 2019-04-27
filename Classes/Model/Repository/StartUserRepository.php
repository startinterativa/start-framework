<?php
namespace StartInterativa\StartFramework\Model\Repository;

use Doctrine\ORM\EntityRepository;
use StartInterativa\StartFramework\Model\ORM\StartUser;

class StartUserRepository extends EntityRepository {
    
    public function login($username, $password) {
        
        $user = $this->findOneBy(array('username' => $username));
        
        if (!is_null($user) && crypt($password, $user->password) == $user->password) {
            $user->lastLogin = time();
            $GLOBALS['db']['orm']->persist($user);
            $GLOBALS['db']['orm']->flush();

            return $this->postProcess($user);
        }
        
        return 0;
    }

    public function get($id) {
        $user = $this->find($id);
        return $this->postProcess($user);
    }

    private function postProcess(StartUser $user) {
        if($user->config) {
            $user->config = json_decode($user->config, true);
        }
        return $user;
    }
}