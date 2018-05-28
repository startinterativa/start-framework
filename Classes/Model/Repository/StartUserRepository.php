<?php
namespace StartInterativa\StartFramework\Model\Repository;

use Doctrine\ORM\EntityRepository;

class StartUserRepository extends EntityRepository {
    
    public function login($username, $password) {
        
        $user = $this->findOneBy(array('username' => $username));
        
        if (!is_null($user) && crypt($password, $user->password) == $user->password) {
            $user->lastLogin = time();
            $GLOBALS['db']['orm']->persist($user);
            $GLOBALS['db']['orm']->flush();
            return $user;
        }
        
        return 0;
    }
}